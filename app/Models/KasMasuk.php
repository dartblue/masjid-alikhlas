<?php

    function index()
    {
        $penerimaan = KasMasuk::orderBy('tanggal')->get();
        return view('admin.kas.penerimaan', compact('penerimaan'));
    }

?>
