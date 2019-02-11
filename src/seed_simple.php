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

    private static $seed_file_tmp = '../data/seed/key_private.seed';

    private static $salt_prefix = 'mnemonic';
    private static $entropy_algo_size = 128;
    private static $entropy_algo = 'sha512'; // 128 bits

    public $password;
    public $word_list = array();
    public $word_list_count = 0;

    public function seed_init(string $wordlist_file)
    {
        $this->word_list = json_decode(file_get_contents($wordlist_file));

        return true;
    }

    static public function bitreverse($bitstring, $size)
    {

        $mask = ~0;
        while ($size > 0) {

            $size = $size >> 1;
            $mask = $mask ^ ($mask << $size);
            $bitstring = (($bitstring >> $size) & $mask) | ((~$mask) & ($bitstring << $size));
        }
        return $bitstring;
    }

    function mnemonic_gen()
    {

        $word_list = array();
        $rsa_private_key = self::rsa_private_key_get();
        $rsa_private_key_master = hash(self::entropy_algo, $rsa_private_key, true);
        $rsa_private_key_master_bin_str = sprintf("%04b", $rsa_private_key_master);
        $checksum_str = substr($rsa_private_key_master_bin_str, -4);
        list($checksum) = sscanf($checksum_str, '%04b');
        $entropy132bits = $rsa_private_key_master . $checksum; // @todo concat

        $entropy132bits_bin_str = sprintf("%04b", $entropy132bits);
        $entropy132bits_bin_str_parts = str_split($entropy132bits_bin_str, 1);

        foreach ($entropy132bits_bin_str_parts as $entropy132bits_bin_str_part) {

            $word_list[] = $this->word_list[$entropy132bits_bin_str_part];
        }
        file_put_contents(self::$seed_file_tmp, implode('', $word_list));

        return true;
    }
}
