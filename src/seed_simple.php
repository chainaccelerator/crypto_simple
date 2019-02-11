<?

trait Seed_simple
{
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

    private static $salt_prefix = 'mnemonic';
    private static $entropy_algo_size = 128;
    private static $entropy_algo = 'sha512'; // 128 bits

    public $word_list;
    public $mnemonic;

    public function seed_init(string $wordlist_file)
    {
        $this->word_list = json_decode(file_get_contents($wordlist_file));

        return true;
    }

    public function mnemonic_gen()
    {
        $mnemonic = array();
        $grain = self::grain_get();
        $rsa_private_key = self::rsa_private_key_get();
        $rsa_private_key_master = hash(self::entropy_algo, $rsa_private_key . $grain, true);
        $rsa_private_key_master_bin_str = sprintf("%04b", $rsa_private_key_master);
        $checksum_str = substr($rsa_private_key_master_bin_str, -4);
        list($checksum) = sscanf($checksum_str, '%04b');
        $entropy132bits = $rsa_private_key_master . $checksum;

        $entropy132bits_bin_str = sprintf("%132b", $entropy132bits);
        $entropy132bits_bin_str_parts = str_split($entropy132bits_bin_str, 11);

        foreach ($entropy132bits_bin_str_parts as $entropy132bits_bin_str_part) {

            list($index_bin) = sscanf($entropy132bits_bin_str_part, '%11b');
            $index_dec = bindec($index_bin);
            $mnemonic[] = $this->word_list[$index_dec];
        }
        $this->mnemonic = implode(' ', $mnemonic);

        return $this->mnemonic;
    }

    private static function grain_get()
    {
        $grain = file_get_contents(self::$seed_grain_file);

        return $grain;
    }

    public function world_list_get()
    {

        return $this->word_list;
    }

    public function private_key_restore($mnemonic)
    {

        $this->mnemonic = $mnemonic;
        $mnemonic_array = explode(' ', $this->mnemonic);

        foreach ($mnemonic_array as $word) {

            $word_bin_str = sprintf("%11b", $word);
            list($word_bin) = sscanf($word_bin_str, '%11b');

        }
    }
}
