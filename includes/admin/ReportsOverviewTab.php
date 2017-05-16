<?php

namespace SmartcatSupport\admin;

use smartcat\admin\MenuPageTab;

class ReportsOverviewTab extends MenuPageTab {

    private $date;

    private $predefined_ranges;

    public function __construct() {

        parent::__construct( array( 'title' => __( 'Overview', \SmartcatSupport\PLUGIN_ID ) ) );

        $this->predefined_ranges = array(
            'last_week'     => __( 'Last 7 Days', \SmartcatSupport\PLUGIN_ID ),
            'this_month'    => __( 'This Month', \SmartcatSupport\PLUGIN_ID ),
            'last_month'    => __( 'Last Month', \SmartcatSupport\PLUGIN_ID ),
            'this_year'     => __( 'This Year', \SmartcatSupport\PLUGIN_ID ),
            'custom'        => __( 'Custom', \SmartcatSupport\PLUGIN_ID ),
        );

        $this->date = new \DateTimeImmutable();
    }

    private function get_data() {
        $start = date_create(
            (isset( $_GET['start_year'] )  ? $_GET['start_year']  : '' ) . '-' .
            ( isset( $_GET['start_month'] ) ? $_GET['start_month'] : '' ) . '-' .
            ( isset( $_GET['start_day'] )   ? $_GET['start_day']   : '' )
        );

        $end = date_create(
        ( isset( $_GET['end_year'] )  ? $_GET['end_year']  : '' ) . '-' .
            ( isset( $_GET['end_month'] ) ? $_GET['end_month'] : '' ) . '-' .
            ( isset( $_GET['end_day'] )   ? $_GET['end_day']   : '' )
        );

        if( !$start || !$end ) {
            $start = $this->default_start();
            $end = $this->date;
        }

        return \SmartcatSupport\statprocs\count_tickets( $start, $end );
    }

    private function graph_data( $data ) { ?>

        <script>

            jQuery(document).ready( function () {

                var chart = new Chartist.Line('#ticket-overview-chart', {
                    labels: <?php echo wp_json_encode( array_keys( $data ) ); ?>,
                    series: [{
                        name: 'opened-tickets',
                        data: <?php echo wp_json_encode( array_column( $data, 'opened' ) ); ?>
                    }, {
                        name: 'closed-tickets',
                        data: <?php echo wp_json_encode( array_column( $data, 'closed' ) ); ?>
                    }]
                }, {
                    margin: {
                        right: '30px'
                    },
                    fullWidth: true,
                    series: {
                        'opened-tickets': {
                            lineSmooth: false,
                            showArea: true
                        },
                        'closed-tickets': {
                            lineSmooth: false
                        }
                    },
                    axisY: {
                        onlyInteger: true
                    },
                    axisX: {
                        labelInterpolationFnc: function(value, index, labels) {

                            if(labels.length < 28) {
                                value = moment(value).format('MMM D');
                            } else if(labels.length < 32) {
                                value = index % 2 === 0 ? moment(value).format('MMM D') : null;
                            } else {
                                value = moment(value).format('MMM');
                            }

                            return value;
                        }
                    },
                    plugins: [
                        Chartist.plugins.tooltip({
                            appendToBody: true
                        }),
                        Chartist.plugins.legend({
                            legendNames: ['Opened', 'Closed']
                        })
                    ]
                });

                chart.on('created', function(context) {
                    context.svg.elem('rect', {
                        x: context.chartRect.x1,
                        y: context.chartRect.y2,
                        width: context.chartRect.width(),
                        height: context.chartRect.height(),
                        fill: 'none',
                        stroke: '#e5e5e5',
                        'stroke-width': '1px'
                    })
                });

            });

        </script>

        <div class="stats-chart-wrapper"><div id="ticket-overview-chart" class="ct-chart ct-golden-section"></div></div>

    <?php }

    public function render() { ?>

        <div class="stats-overview stat-section">
            <form method="get">
                <input type="hidden" name="page" value="<?php echo $this->page; ?>" />
                <input type="hidden" name="tab" value="<?php echo $this->slug; ?>" />
                <div class="stats-header">
                    <div class="form-inline">
                        <div class="control-group">
                            <select name="range" class="date-range-select form-control">

                                <?php foreach($this->predefined_ranges as $option => $label ) : ?>

                                    <option value="<?php echo $option; ?>"
                                        <?php selected( $option, isset( $_GET['range'] ) ? $_GET['range'] : '' ); ?>>

                                        <?php echo $label; ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>
                        </div>
                        <div class="date-range control-group <?php echo isset( $_GET['range'] ) && $_GET['range'] == 'custom' ? '' : 'hidden'; ?>">
                            <span class="start_date">
                                <?php

                                    $default = $this->default_start();

                                    $this->date_picker(
                                        'start_',
                                        $default->format( 'n' ),
                                        $default->format( 'j' ),
                                        $default->format( 'Y' )
                                    );

                                ?>
                            </span>
                            <span>—</span>
                            <span class="end_date">
                                <?php

                                    $this->date_picker(
                                        'end_',
                                        $this->date->format( 'n' ),
                                        $this->date->format( 'j' ),
                                        $this->date->format( 'Y' )
                                    );

                                ?>
                            </span>
                        </div>
                        <div class="control-group">
                            <button type="submit" class="form-control button button-secondary"><?php _e( 'Go', \SmartcatSupport\PLUGIN_ID ); ?></button>
                        </div>
                    </div>
                </div>

            </form>

            <?php $this->graph_data( $this->get_data() ); ?>

        </div>

    <?php }

    private function default_start() {
        return $this->date->sub( new \DateInterval( 'P7D' ) );
    }

    private function date_picker( $prefix = '', $month = '', $day = '', $year = '' ) { ?>

        <select name="<?php echo $prefix; ?>month">

            <?php for( $m = 1; $m <= 12; $m++ ) : ?>

                <option value="<?php echo $m; ?>"

                    <?php selected( isset( $_GET["{$prefix}month"] ) ? $_GET["{$prefix}month"] : $month, $m ); ?>>

                    <?php _e( date('F', mktime(0, 0, 0, $m, 1 ) ), \SmartcatSupport\PLUGIN_ID ); ?>

                </option>

            <?php endfor; ?>

        </select>

        <select name="<?php echo $prefix; ?>day">

            <?php for( $d = 1; $d <= 31; $d++ ) : ?>

                <option value="<?php echo $d; ?>"

                    <?php selected( isset( $_GET["{$prefix}day"] ) ? $_GET["{$prefix}day"] : $day, $d ); ?>><?php echo $d; ?></option>

            <?php endfor; ?>

        </select>

        <?php $this_year = $this->date->format( 'Y' ); ?>

        <select name="<?php echo $prefix; ?>year">

            <?php for( $y = $this_year; $y >= $this_year - 10; $y-- ) : ?>

                <option value="<?php echo $y; ?>"

                    <?php selected( isset( $_GET["{$prefix}year"] ) ? $_GET["{$prefix}year"] : $year, $y ); ?>><?php echo $y; ?></option>

            <?php endfor; ?>

        </select>

    <?php }

}