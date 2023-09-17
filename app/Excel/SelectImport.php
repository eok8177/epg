<?php

namespace App\Excel;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class SelectImport implements ToCollection
{
    use Importable;
    use RegistersEventListeners;

    public static function beforeImport(BeforeImport $event)
    {
      $options = LIBXML_COMPACT | LIBXML_PARSEHUGE;

      \PhpOffice\PhpSpreadsheet\Settings::setLibXmlLoaderOptions($options);
    }

    public function collection(Collection $rows)
    {
        //
    }
}
