<?php

namespace App\Enums;

enum DocumentType: string
{
    case RESI = 'RESI';
    case SURAT_JALAN = 'SURAT_JALAN';
    case FOTO_BARANG = 'FOTO_BARANG';
    case LAINNYA = 'LAINNYA';

    public function label(): string
    {
        return match($this) {
            self::RESI => 'Resi',
            self::SURAT_JALAN => 'Surat Jalan',
            self::FOTO_BARANG => 'Foto Barang',
            self::LAINNYA => 'Lainnya',
        };
    }
}