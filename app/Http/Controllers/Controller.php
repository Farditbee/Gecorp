<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected array $title = [];
    protected array $label = [
        'Data Master', 'Data Transaksi', 'Laporan'
    ];
}
