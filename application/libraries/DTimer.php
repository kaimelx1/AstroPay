<?php

class DTimer
{
    protected static $startTime;
    protected static $points = array();

    public static function run()
    {
        self::$startTime = microtime(true);
    }

    public static function log($message = '')
    {
        if (self::$startTime === null)
            self::run();

        self::$points[] = array('message' => $message, 'time' => sprintf('%0.3f', microtime(true) - self::$startTime));
    }

    public static function show($type = 's')
    {
            $time_type = array(
                's' => '1',
                'ms' => '1000',
                'mcs' => '1000000'
            );

            $oldtime = 0;

        echo '<a style="position:fixed; right:5px; bottom:5px; z-index:1000;" class="btn btn-xs btn-warning" onclick="$(\'.sqlspeed\').toggle();"><i class="fa fa-clock-o"></i></a>';

        // добавить hidden в table когда подключу бустрап и фа-фа иконки
            echo '
    <table onclick="$(this).toggle();" border="1" style="cursor:pointer;color: #000; border: 1px solid #ec971f; width:auto !important; position:fixed; right:5px; bottom: 5px; z-index:1000; background:#fff !important" class="sqlspeed" hidden>
    <tr>
        <th style="text-align: center !important; padding: 3px;">Step</th>
        <th style="text-align: center !important; padding: 3px;">Diff</th>
        <th style="text-align: center !important; padding: 3px;">Time</th>
    </tr>
    ';

            foreach (self::$points as $item) {

                $message = $item['message'];
                $time = $item['time'] * $time_type[$type];
                $diff = ($item['time'] - $oldtime) * $time_type[$type];

                echo "
    <tr>
        <td style='text-align: center !important; padding: 3px;'>{$message}</td>
        <td style='text-align: center !important; padding: 3px;'>{$diff}</td>
        <td style='text-align: center !important; padding: 3px;'>{$time}</td>
    </tr>
    ";

                $oldtime = $item['time'];
            };
            echo "</table>\n";
        }

}