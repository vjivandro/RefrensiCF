<?php

  include 'koneksi.php';

  /**
   * Crud class
   * turunan dario class koneksi
   */
  class Crud extends koneksi
  {
    // untuk mengambil function dari parent(koneksi)
    public function __construct()
    {
      parent::__construct();
    }

    /**
      * function readGejala
      * mengambil tabel dari gejala
      * return array isi tabel
     */
    public function readGejala()
    {
      $sql = "SELECT * FROM gejala";
      $result = $this->conn->query($sql);

      // merubah data tabel menjadi array
      $row = [];
      while ($row = $result->fetch_assoc()) {
			  $rows[] = $row;
		  }

		  return $rows;

    }

    /**
      * funtion getGejala
      * mengambil data sebagian dari tabel gejala
     */
    public function getGejala($value)
    {
      $sql = "SELECT * FROM gejala WHERE id_gejala IN ($value)";
      $result = $this->conn->query($sql);

      // merubah data tabel menjadi array
      $row = [];
      while ($row = $result->fetch_assoc()) {
			  $rows[] = $row;
		  }

		  return $rows;
    }

    public function getPenyakit($value)
    {
      $sql = "SELECT * FROM penyakit WHERE id_penyakit IN ($value)";
      $result = $this->conn->query($sql);

      // merubah data tabel menjadi array
      $row = [];
      while ($row = $result->fetch_assoc()) {
			  $rows[] = $row;
		  }

		  return $rows;
    }

    /**
      * function joinGetPengetahuan
      * merupakan tabel join antara pengetahuan, gejala, penyakit
      * return array dari tabel join
     */
    public function joinGetPengetahuan($value='')
    {
      // p, g , pyt merupakan inisialisasi dari tabel yang dituju
      $sql = "SELECT * FROM pengetahuan p
        JOIN gejala g ON p.id_gejala = g.id_gejala
        JOIN penyakit pyt ON p.kode_penyakit = pyt.kode_penyakit
        WHERE p.id_gejala IN ($value)
        GROUP BY p.kode_penyakit ORDER BY p.kode_penyakit";

      $result = $this->conn->query($sql);

      // merubah data tabel menjadi array
      $row = [];
      while ($row = $result->fetch_assoc()) {
			  $rows[] = $row;
		  }

		  return $rows;
    }

    public function getPengetahuan($value)
    {

      $sql = "SELECT * FROM gejala g 
        JOIN pengetahuan p ON p.id_gejala = g.id_gejala
        JOIN penyakit pyt ON p.kode_penyakit = pyt.kode_penyakit
        WHERE p.id_gejala IN ($value)";

      $result = $this->conn->query($sql);

      // merubah data tabel menjadi array
      $row = [];
      while ($row = $result->fetch_assoc()) {
			  $rows[] = $row;
		  }

		  return $rows;
    }

  }


 ?>
