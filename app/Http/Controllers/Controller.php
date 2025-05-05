<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected array $title = [];
    protected array $label = [
        'Data Master', 'Transaksi', 'Rekapitulasi', 'Retur', 'Laporan Keuangan', 'Jurnal Keuangan', 'Distribusi'
    ];
}
