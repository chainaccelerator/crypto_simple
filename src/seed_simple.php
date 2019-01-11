<?

trait Seed_simple
{
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

    private static $seed_wordlist_chinese_simplifed = '../data/wordlists/chinese_simplified.json';
    private static $seed_wordlist_chinese_traditional = '../data/wordlists/chinese_traditional.json';
    private static $seed_wordlist_english = '../data/wordlists/english.json';
    private static $seed_wordlist_french = '../data/wordlists/french.json';
    private static $seed_wordlist_italian = '../data/wordlists/italian.json';
    private static $seed_wordlist_japanase = '../data/wordlists/japanese.json';
    private static $seed_wordlist_korean = '../data/wordlists/korean.json';
    private static $seed_wordlist_spanish = '../data/wordlists/spanish.json';
    private static $seed_wordlist_default = '../data/wordlists/english.json';

    private static $strength = 128;

    private static function init()
    {

        self::$seed_wordlist_chinese_simplifed = file_get_contents(self::$seed_wordlist_chinese_simplifed);
        self::$seed_wordlist_chinese_traidtional = file_get_contents(self::$seed_wordlist_chinese_traditional);
        self::$seed_wordlist_english = file_get_contents(self::$seed_wordlist_english);
        self::$seed_wordlist_french = file_get_contents(self::$seed_wordlist_french);
        self::$seed_wordlist_italian = file_get_contents(self::$seed_wordlist_italian);
        self::$seed_wordlist_japanase = file_get_contents(self::$seed_wordlist_japanase);
        self::$seed_wordlist_korean = file_get_contents(self::$seed_wordlist_korean);
        self::$seed_wordlist_spanish = file_get_contents(self::$seed_wordlist_spanish);
        self::$seed_wordlist_default = file_get_contents(self::$seed_wordlist_default);

        return true;
    }

    public static function lpad(string $str, string $padString, string $length)
    {

        while (strlen($str) < $length) $str = $padString + $str;

        return $str;
    }

    public static function binaryToByte($bin)
    {

        return intval($bin, 2);
    }

    public static function bytesToBinary($bytes)
    {

        return self::lpad($bytes, '0', 8);
    }

    public static function deriveChecksumBits($entropyBuffer)
    {

        $ENT = strlen($entropyBuffer) * 8;
        $CS = $ENT / 32;
        $hash = hash('sha256', $entropyBuffer);

        return self::bytesToBinary(array_slice($hash, 0, $CS));
    }

    public static function salt($password)
    {

        return 'mnemonic' . $password;
    }

    function mnemonicToSeed($mnemonic, $password)
    {

        $mnemonicBuffer = Buffer . from(unorm . nfkd(mnemonic), 'utf8');
        $saltBuffer = Buffer . from(salt(unorm . nfkd(password)), 'utf8');

        return pbkdf2($mnemonicBuffer, $saltBuffer, 2048, 64, 'sha512');
    }

    function mnemonicToSeedHex($mnemonic, $password)
    {

        return $this->mnemonicToSeed($mnemonic, $password) . toString('hex');
    }

    function mnemonicToEntropy($mnemonic, $wordlist)
    {

        wordlist = wordlist || DEFAULT_WORDLIST

      var words = unorm . nfkd(mnemonic) . split(' ')
      if (words . length % 3 !== 0) throw new Error(INVALID_MNEMONIC)

      // convert word indices to 11 bit binary strings
      var bits = words . map(function (word) {
            var
            index = wordlist . indexOf(word)
        if (index === -1) throw new Error(INVALID_MNEMONIC)

        return lpad(index . toString(2), '0', 11)
      }) . join('')

      // split the binary string into ENT/CS
      var dividerIndex = Math . floor(bits . length / 33) * 32
      var entropyBits = bits . slice(0, dividerIndex)
      var checksumBits = bits . slice(dividerIndex)

      // calculate the checksum and compare
      var entropyBytes = entropyBits . match(/(.{
        1,8})/g).map(binaryToByte)
      if (entropyBytes . length < 16) throw new Error(INVALID_ENTROPY)
      if (entropyBytes . length > 32) throw new Error(INVALID_ENTROPY)
      if (entropyBytes . length % 4 !== 0) throw new Error(INVALID_ENTROPY)

      var entropy = Buffer . from(entropyBytes)
      var newChecksum = deriveChecksumBits(entropy)
      if (newChecksum !== checksumBits) throw new Error(INVALID_CHECKSUM)

      return entropy . toString('hex')
    }

    function entropyToMnemonic(entropy, wordlist) {
        if (!Buffer . isBuffer(entropy)) entropy = Buffer . from(entropy, 'hex')
  wordlist = wordlist || DEFAULT_WORDLIST

  // 128 <= ENT <= 256
  if (entropy . length < 16) throw new TypeError(INVALID_ENTROPY)
  if (entropy . length > 32) throw new TypeError(INVALID_ENTROPY)
  if (entropy . length % 4 !== 0) throw new TypeError(INVALID_ENTROPY)

  var entropyBits = bytesToBinary([] . slice . call(entropy))
  var checksumBits = deriveChecksumBits(entropy)

  var bits = entropyBits + checksumBits
  var chunks = bits . match(/(.{
            1,11})/g)
  var words = chunks . map(function (binary) {
            var
            index = binaryToByte(binary)
    return wordlist[index]
  })

  return wordlist === JAPANESE_WORDLIST ? words . join('\u3000') : words . join(' ')
}

    function generateMnemonic($rng, $wordlist)
    {

        if (self::$strength % 32 !== 0) {

            // throw new TypeError(self::$INVALID_ENTROPY);
            return false;
        }
        return $this->entropyToMnemonic(rng(self::$strength / 8), $wordlist);
    }

    function validateMnemonic($mnemonic, $wordlist)
    {

        return $this->mnemonicToEntropy($mnemonic, $wordlist);

        return true;
}
