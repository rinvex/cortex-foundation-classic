<?php

declare(strict_types=1);

namespace Cortex\Foundation\Importers;

use Maatwebsite\Excel\Files\ExcelFile;

class DefaultImporter extends ExcelFile
{
    /**
     * The importer config.
     *
     * @var array
     */
    public $config = [
        'name' => 'name',
        'log' => true,
    ];

    /**
     * {@inheritdoc}
     */
    protected $delimiter = ';';

    /**
     * {@inheritdoc}
     */
    protected $enclosure = '"';

    /**
     * {@inheritdoc}
     */
    protected $lineEnding = '\r\n';

    /**
     * {@inheritdoc}
     */
    public function getFile()
    {
        return request()->file('file');
    }
}
