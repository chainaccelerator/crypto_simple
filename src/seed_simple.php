<?

trait Seed_simple
{
    private static $seed_private_key_master_dir = '../data/seed/';
    private static $seed_grain_file = '../data/seed/grain.txt';
    private static $SEED_CHINESE_SIMPLIFIED_WORDLIST_FILE = '../data/wordlists/chinese_simplified.json';
    private static $SEED_CHINESE_TRADITIONAL_WORDLIST_FILE = '../data/wordlists/chinese_traditional.json';
    private static $SEED_ENGLISH_WORDLIST_FILE = '../data/wordlists/english.json';
    private static $SEED_FRENCH_WORDLIST_FILE = '../data/wordlists/french.json';
    private static $SEED_ITALIAN_WORDLIST_FILE = '../data/wordlists/italian.json';
    private static $SEED_JAPANESE_WORDLIST_FILE = '../data/wordlists/japanese.json';
    private static $SEED_KOREAN_WORDLIST_FILE = '../data/wordlists/korean.json';
    private static $SEED_SPANISH_WORDLIST_FILE = '../data//wordlists/spanish.json';
    private static $SEED_DEFAULT_WORDLIST_FILE = '../data/wordlists/english.json';

    private static $SEED_INVALID_MNEMONIC = 'Invalid mnemonic';
    private static $SEED_INVALID_ENTROPY = 'Invalid entropy';
    private static $SEED_INVALID_CHECKSUM = 'Invalid mnemonic checksum';

    private static $seed_entropy_algo = 'sha512'; // 128 bits

    public $seed_word_list;
    public $seed_mnemonic;

    public function seed_init(string $wordlist_file)
    {
        $this->seed_word_list = json_decode(file_get_contents($wordlist_file));

        return true;
    }

    public function seed_private_key_master_str_get(){

        $grain = self::seed_grain_get();
        $rsa_private_key = self::rsa_private_key_get();
        $private_key_master = hash(self::$seed_entropy_algo, $rsa_private_key . $grain, true);
        $private_key_master_bin_str = sprintf("%128b", $private_key_master);
        $checksum_str = substr($private_key_master_bin_str, -4);

        $seed_private_key_master_str = new stdClass();
        $seed_private_key_master_str->private_key_master = $private_key_master_bin_str;
        $seed_private_key_master_str->checksum = $checksum_str;
        $seed_private_key_master_str->rsa_private_key_list = array();

        $keys = new stdClass();
        $keys->private = $rsa_private_key;
        $keys->public = self::rsa_public_key_get();
        $seed_private_key_master_str->rsa_private_key_list[] = $keys;

        return $seed_private_key_master_str;
    }

    private static function seed_mnemonic_file_write(stdclass $seed_private_key_master_str){

        $file = self::$seed_private_key_master_dir.$seed_private_key_master_str->private_key_master.'.key';
        $data = json_encode($seed_private_key_master_str);

        file_put_contents($file, $data);

        return $data;
    }

    private static function seed_mnemonic_file_read(string $private_key_master){

        $file = self::$seed_private_key_master_dir.$private_key_master.'.key';
        $data = file_get_contents($file);
        $json_obj = json_decode($data);

        return $json_obj;
    }

    public function seed_mnemonic_gen()
    {
        $mnemonic = array();
        $seed_private_key_master_str = $this->seed_private_key_master_str_get();

        list($private_key_master) = sscanf($seed_private_key_master_str->private_key_master, '%128b');
        list($checksum) = sscanf($seed_private_key_master_str->checksum, '%04b');
        $entropy132bits = $private_key_master . $checksum;

        $entropy132bits_bin_str = sprintf("%132b", $entropy132bits);
        $entropy132bits_bin_str_parts = str_split($entropy132bits_bin_str, 11);

        foreach ($entropy132bits_bin_str_parts as $entropy132bits_bin_str_part) {

            list($index_bin) = sscanf($entropy132bits_bin_str_part, '%11b');
            $index_dec = bindec($index_bin);
            $mnemonic[] = $this->word_list[$index_dec];
        }
        $mnemonic_str = implode(' ', $mnemonic);

        $this->seed_mnemonic_set($mnemonic_str);

        self::seed_mnemonic_file_write(stdclass $seed_private_key_master_str);

        return $this->mnemonic;
    }

    private static function seed_grain_get()
    {
        $grain = file_get_contents(self::$seed_grain_file);

        return $grain;
    }

    public function seed_mnemonic_get()
    {

        return $this->seed_mnemonic;
    }

    public function seed_mnemonic_set(string $mnemonic)
    {

        $this->seed_mnemonic = $mnemonic;

        return true;
    }

    public function seed_private_key_restore_control(string $private_key_master_str_test, string $checksum_str_test){

        $seed_private_key_master = $this->seed_private_key_master_str_get();

        if($seed_private_key_master->private_key_master  === $private_key_master_str_test && $seed_private_key_master->checksum === $checksum_str_test) {

            return true;
        }
        return false;
    }

    public function seed_private_key_restore(string $mnemonic)
    {

        $this->seed_mnemonic_set($mnemonic);

        $word_bin_str = '';
        $mnemonic_array = explode(' ', $this->seed_mnemonic);

        foreach ($mnemonic_array as $word) {

            $index_dec = in_array($word, $this->seed_word_list);
            $index_bin = decbin($index_dec);
            $word_bin_str .= sprintf("%11b", $index_bin);
        }
        $private_key_master_str = substr($word_bin_str, 0, -4);
        $checksum_str = substr($word_bin_str, -4);
        $control = $this->seed_private_key_restore_control($private_key_master_str, $checksum_str);

        if($control === false) return false;

        $json = self::seed_mnemonic_file_read($private_key_master_str);

        return $json;
    }
}
