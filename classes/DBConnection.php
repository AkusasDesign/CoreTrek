<?php

/**
 * Class DBConnection
 */
class DBConnection extends PDO {

    protected $numRows;
    protected $numQueries;

    /**
     * DBConnection constructor.
     * @param string $iniFile
     * @throws Exception
     */
    public function __construct(string $iniFile = 'settings.ini') {

        $settings = self::parseSettings($iniFile);

        if (is_array($settings)) {
            $dsn = $settings['database']['driver']
                . ':host=' . $settings['database']['host']
                . ';dbname=' . $settings['database']['db_name'];

            parent::__construct($dsn, $settings['database']['username'], $settings['database']['password']);
        }
    }

    /**
     * Prepares, executes and fetches the result of the give query.
     *
     * @param $sqlStatement
     * @param array $parameters
     * @return mixed
     */
    public function doQuery($sqlStatement, $parameters = array()) {
        $query = $this->prepare($sqlStatement);
        $query->execute($parameters);
        $this->numRows = $query->rowCount();
        $this->numQueries++;
        $result = $query->fetchAll();
        return $result;
    }

    /**
     * @return mixed
     */
    public function getNumRows() {
        return $this->numRows;
    }

    /**
     * @return mixed
     */
    public function getNumQueries() {
        return $this->numQueries;
    }

    /**
     * Parses a .ini-style file.
     *
     * @see http://php.net/manual/en/function.parse-ini-file.php for example syntax
     *
     * @param string $filename
     * @return array|bool|null
     * @throws Exception
     */
    protected static function parseSettings(string $filename) {
        if (!file_exists($filename)) {
            echo "<pre>ERROR: unable to locate the settings file: [".$filename."]</pre>";
            return null;
        }

        $settings = parse_ini_file($filename, true);
        if (!$settings) {
            throw new Exception("Unable to open/parse INI file [" . $filename . "].");
        }
        return $settings;
    }
}