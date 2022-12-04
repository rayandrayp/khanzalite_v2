<?php

namespace Plugins\Master\Src;

use Systems\Lib\QueryWrapper;
use Systems\MySQL;

class MasterBerkasDigital
{

    protected function db($table)
    {
        return new QueryWrapper($table);
    }

    public function getIndex()
    {

      $totalRecords = $this->mysql('master_berkas_digital')
        ->select('kode')
        ->toArray();
      $offset         = 10;
      $return['halaman']    = 1;
      $return['jml_halaman']    = ceil(count($totalRecords) / $offset);
      $return['jumlah_data']    = count($totalRecords);

      $return['list'] = $this->mysql('master_berkas_digital')
        ->desc('kode')
        ->limit(10)
        ->toArray();

      return $return;

    }

    public function anyForm()
    {
        if (isset($_POST['kode'])){
          $return['form'] = $this->mysql('master_berkas_digital')->where('kode', $_POST['kode'])->oneArray();
        } else {
          $return['form'] = [
            'kode' => '',
            'nama' => ''
          ];
        }

        return $return;
    }

    public function anyDisplay()
    {

        $perpage = '10';
        $totalRecords = $this->mysql('master_berkas_digital')
          ->select('kode')
          ->toArray();
        $offset         = 10;
        $return['halaman']    = 1;
        $return['jml_halaman']    = ceil(count($totalRecords) / $offset);
        $return['jumlah_data']    = count($totalRecords);

        $return['list'] = $this->mysql('master_berkas_digital')
          ->desc('kode')
          ->offset(0)
          ->limit($perpage)
          ->toArray();

        if(isset($_POST['cari'])) {
          $return['list'] = $this->mysql('master_berkas_digital')
            ->like('kode', '%'.$_POST['cari'].'%')
            ->orLike('nama', '%'.$_POST['cari'].'%')
            ->desc('kode')
            ->offset(0)
            ->limit($perpage)
            ->toArray();
          $jumlah_data = count($return['list']);
          $jml_halaman = ceil($jumlah_data / $offset);
        }
        if(isset($_POST['halaman'])){
          $offset     = (($_POST['halaman'] - 1) * $perpage);
          $return['list'] = $this->mysql('master_berkas_digital')
            ->desc('kode')
            ->offset($offset)
            ->limit($perpage)
            ->toArray();
          $return['halaman'] = $_POST['halaman'];
        }

        return $return;
    }

    public function postSave()
    {
      if (!$this->mysql('master_berkas_digital')->where('kode', $_POST['kode'])->oneArray()) {
        $query = $this->mysql('master_berkas_digital')->save($_POST);
      } else {
        $query = $this->mysql('master_berkas_digital')->where('kode', $_POST['kode'])->save($_POST);
      }
      return $query;
    }

    public function postHapus()
    {
      return $this->mysql('master_berkas_digital')->where('kode', $_POST['kode'])->delete();
    }

    protected function mysql($table = NULL)
    {
        return new MySQL($table);
    }

}
