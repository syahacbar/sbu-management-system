<?php

namespace App\Helpers;

use ZipArchive;
use SimpleXMLElement;
use Exception;

class SimpleXlsxReader
{
    /**
     * Read an XLSX file and return it as a 2D array of rows and columns.
     *
     * @param string $filePath
     * @return array<int, array<int, string>>
     * @throws Exception
     */
    public static function read(string $filePath): array
    {
        if (!class_exists('ZipArchive')) {
            throw new Exception("Ekstensi PHP ZipArchive tidak aktif. Silakan aktifkan terlebih dahulu.");
        }

        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new Exception("Gagal membuka berkas Excel (XLSX). Pastikan format file benar.");
        }

        // 1. Load shared strings to resolve text values in cell sheets
        $sharedStrings = [];
        $sharedStringsIndex = $zip->locateName('xl/sharedStrings.xml');
        if ($sharedStringsIndex !== false) {
            $xmlContent = $zip->getFromIndex($sharedStringsIndex);
            $xml = simplexml_load_string($xmlContent);
            if ($xml) {
                foreach ($xml->si as $si) {
                    if (isset($si->t)) {
                        $sharedStrings[] = (string) $si->t;
                    } elseif (isset($si->r)) {
                        // Concatenate rich text runs
                        $text = '';
                        foreach ($si->r as $r) {
                            $text .= (string) $r->t;
                        }
                        $sharedStrings[] = $text;
                    } else {
                        $sharedStrings[] = '';
                    }
                }
            }
        }

        // 2. Load the first sheet
        $sheetIndex = $zip->locateName('xl/worksheets/sheet1.xml');
        if ($sheetIndex === false) {
            $zip->close();
            throw new Exception("Berkas Excel tidak memiliki sheet yang valid (sheet1.xml tidak ditemukan).");
        }

        $xmlContent = $zip->getFromIndex($sheetIndex);
        $xml = simplexml_load_string($xmlContent);
        if (!$xml) {
            $zip->close();
            throw new Exception("Gagal memproses XML worksheet.");
        }

        $rows = [];
        foreach ($xml->sheetData->row as $row) {
            $rowData = [];
            foreach ($row->c as $cell) {
                $cellRef = (string) $cell['r']; // e.g. "A1", "B5"
                // Extract column letter
                preg_match('/^[A-Z]+/', $cellRef, $matches);
                if (empty($matches)) {
                    continue;
                }
                $colLetter = $matches[0];
                $colIndex = self::columnLetterToIndex($colLetter);

                $value = '';
                if (isset($cell->v)) {
                    $value = (string) $cell->v;
                    $type = (string) $cell['t'];

                    if ($type === 's') {
                        // Shared string lookup
                        $value = $sharedStrings[(int) $value] ?? '';
                    }
                } elseif (isset($cell->is->t)) {
                    $value = (string) $cell->is->t;
                }

                $rowData[$colIndex] = trim($value);
            }

            if (!empty($rowData)) {
                // Ensure all keys from 0 to max column index are set to prevent undefined offset errors
                $maxCol = max(array_keys($rowData));
                for ($i = 0; $i <= $maxCol; $i++) {
                    if (!isset($rowData[$i])) {
                        $rowData[$i] = '';
                    }
                }
                ksort($rowData);
                $rows[] = $rowData;
            }
        }

        $zip->close();
        return $rows;
    }

    /**
     * Convert Excel column letters (A, B, C...) to 0-based indices.
     */
    private static function columnLetterToIndex(string $letter): int
    {
        $index = 0;
        $length = strlen($letter);
        for ($i = 0; $i < $length; $i++) {
            $index = $index * 26 + (ord($letter[$i]) - 64);
        }
        return $index - 1;
    }
}
