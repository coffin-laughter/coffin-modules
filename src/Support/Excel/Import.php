<?php
/**
 *  +-------------------------------------------------------------
 *  | Coffin [ 花开不同赏，花落不同悲。欲问相思处，花开花落时。 ]
 *  +-------------------------------------------------------------
 *  | Copyright (c) 2025~2025 All rights reserved.
 *  +-------------------------------------------------------------
 *  | @author: coffin's laughter | <chuanshuo_yongyuan@163.com>
 *  +-------------------------------------------------------------
 */

namespace Coffin\Support\Excel;

use Coffin\Exceptions\FailedException;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Validators\ValidationException;

abstract class Import implements
    ToCollection,
    WithChunkReading,
    WithStartRow,
    WithValidation,
    WithEvents
{
    use Importable;
    use RegistersEventListeners;
    use SkipsFailures;

    /**
     * 默认块数
     *
     * @var int
     */
    protected int $chunk = 0;


    /**
     * chunk size
     *
     * @var int
     */
    protected int $chunkSize = 200;

    /**
     * 错误信息
     *
     * @var array
     */
    protected array $err = [];

    /**
     * 导入最大行数
     *
     * @var int
     */
    protected static int $importMaxNum = 5000;

    /**
     * @var array
     */
    protected array $params = [];


    /**
     * 默认的块大小
     *
     * @var int
     */
    protected int $size = 500;

    /**
     * 默认开始行
     *
     * @var int
     */
    protected int $start = 2;

    /**
     * 总条数
     *
     * @var int
     */
    protected static int $total = 0;

    /**
     * @param BeforeImport $event
     * @return void
     */
    public static function beforeImport(BeforeImport $event): void
    {
        $total = $event->getReader()->getTotalRows()['Worksheet'];

        static::$total = $total;

        if ($total > static::$importMaxNum) {
            throw new FailedException(sprintf('最大支持导入数量 %d 条', self::$importMaxNum));
        }
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return $this->chunkSize;
    }

    /**
     * @param string|UploadedFile $filePath
     * @param string|null $disk
     * @param string|null $readerType
     * @return array|int
     */
    public function import(string|UploadedFile $filePath, string $disk = null, string $readerType = null): int|array
    {
        if (empty($filePath)) {
            throw new FailedException('没有上传导入文件');
        }

        if ($filePath instanceof UploadedFile) {
            $filePath = $filePath->store('excel/import/' . date('Ymd') . '/');
        }

        try {
            $this->getImporter()->import(
                $this,
                $filePath,
                $disk ?? $this->disk ?? null,
                $readerType ?? $this->readerType ?? null
            );
        } catch (ValidationException $e) {
            $failures = $e->failures();

            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = sprintf('第%d行错误:%s', $failure->row(), implode('|', $failure->errors()));
            }

            return [
                'error' => $errors,
                'total' => static::$total,
                'path'  => $filePath
            ];
        }

        return static::$total;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        // TODO: Implement rules() method.
        return [];
    }


    /**
     * @param $params
     * @return $this
     */
    public function setParams($params): static
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        // TODO: Implement startRow() method.
        return $this->start;
    }
}
