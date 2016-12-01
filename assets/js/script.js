jQuery(document).ready(function ($) {

    // Bind events
    $(document).on('submit', '.support_form', SupportSystem.submit_form);

    $(window).resize(SupportSystem.resize);

    $(document).on('click', '.trigger', function (e) {
        e.preventDefault();

        SupportSystem[$(this).data('action')] ($(this));

        return;
    } );

    $(document).on('change', '#ticket_filter .form_field', function (e) {
        $('#ticket_filter').find('.filter').parent().removeClass('active');
    });


    var tabs = $('#support_system .tabs').tabs({
        beforeLoad: function( event, ui ) {
            if ( ui.tab.data( 'loaded' ) ) {
                event.preventDefault();
                return;
            }

            ui.jqXHR.success(function() {
                ui.tab.data( 'loaded', true );
            });
        },

        load: function (even, ui) {
            SupportSystem.init_table();
        },

        create: function(event, ui) {
            ui.tab.width(window.innerWidth / 10);
        },

        activate: function(event, ui) {
            ui.newTab.width(window.innerWidth / 10);
        }

    });

    tabs.on('click', '.ui-icon-close', function () {
        var tab = $( this ).closest( 'li' ).remove().attr( 'aria-controls' );
        $( '#' + tab ).remove();
        tabs.tabs( 'refresh' );
    });

    
});
