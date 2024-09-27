<title>Plan Order All Toko - Gecorp</title>
@extends('layouts.main')
@section('content')

<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header">
                    <div class="page-title">
                        <h1 class="card-title"><strong>Data Master - Plan Order</strong></h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('master.index')}}">Dashboard</a></li>
                            <li class="active">Data Plan Order - All Toko</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Content -->
        <div class="content">
            <!-- Animated -->
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">&nbsp;</strong>
                            </div>
                            <div class="card-body">
                                <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Nama Barang</th>
                                            <th>GSS</th>
                                            <th>GEC</th>
                                            <th>PIT</th>
                                            <th>MB</th>
                                            <th>LJK</th>
                                            <th>47</th>
                                            <th>HR</th>
                                            <th>SSR</th>
                                            <th>SNR</th>
                                            <th>OTW</th>
                                            <th>Tot</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>O</td>
                                            <td>Contoh Barang</td>
                                            <td>615</td>
                                            <td>61</td>
                                            <td>77</td>
                                            <th>2</th>
                                            <th>42</th>
                                            <th>921</th>
                                            <th>15</th>
                                            <th>6</th>
                                            <th>17</th>
                                            <th>721</th>
                                            <th>719</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- .animated -->
        </div>
        <!-- /.content -->

        <!-- Footer -->
        @endsection
</body>
</html>
