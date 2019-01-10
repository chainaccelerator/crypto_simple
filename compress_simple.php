<?

trait Compress_simple {

    private static $COMPRESS_STATE_PARAM_POS = 4;
    private static $COMPRESS_LEVEL_PARAM_POS = 1;
    private static $compress_state = true;
    private static $compress_level = 9;

    public static function compress(string $msg) {

      if(self::$compress_state === true) {

        $msg = gzcompress($msg,  self::$compress_level);
      }
      return $msg;
    }

    public static function uncompress(string $msg){

      if(self::$compress_state === true) {

            $msg = gzuncompress($msg);
      }
      return $msg;
    }

}
