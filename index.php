<?php
	// untuk memanggil file
	include 'Crud.php';
	// untuk mendeklarasikan class menjadi variabel
	$crud = new Crud();
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistem Pakar Metode CF (Certainty Factor)</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Georgia, Times New Roman, Times, serif;
	font-size: 13px;
	color: #333333;
}
.style1 {
	color: #000099;
	font-size: 24px;
}
a:link {
	text-decoration: none;
	color: #333333;
}
a:visited {
	text-decoration: none;
	color: #333333;
}
a:hover {
	text-decoration: underline;
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #333333;
}
.style2 {font-weight: bold}
-->
</style></head>

<body>
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#000099">
  <tr>
    <td height="50" bgcolor="#FFFFFF"><span class="style1">Sistem Pakar Metode CF (Certainty Factor)</span></td>
  </tr>
  <tr>
    <td height="35" bgcolor="#FFFFFF"><span class="style2"><a href="index.php">Home</a> | <a href="cf-php-mysql.php">Konsultasi Pakar Metode Certainty Factor</a> | <a href="login.php">Login</a></span></td>
  </tr>
  <tr>
    <td align="center" valign="top" bgcolor="#FFFFFF"><br />
      <strong>Analisa Menggunakan Sistem Pakar Metode CF (Certainty Factor)</strong><br />
      <br />
<form name="form1" method="post" action=""><br>
  <table align="center" width="600" border="1" cellspacing="0" cellpadding="5">
  <tr>
  <td id="ignore" bgcolor="#DBEAF5" width="300"><div align="center"><strong><font size="2" face="Arial, Helvetica, sans-serif"><font size="2">GEJALA</font> </font></strong></div></td>
  <?php
    $arrayName = $crud->readGejala();
		foreach ($arrayName as $r)
	  {
	?>
    <tr>
      <td width="600">
        <input id="gejala<?php echo $r['id_gejala']; ?>" name="gejala[]" type="checkbox" value="<?php echo $r['id_gejala']; ?>">
        <?php echo $r['nama_gejala']; ?><br/>
        </td>
    </tr>
    <?php
      }
     ?>
    <tr>
      <td><input type="submit" name="button" value="Proses"></td>
    </tr>
  </table>
  <br>
</form>
<?php
  if (isset($_POST['button']))
	{
		// mengambil data post(array) dan merubah menjadi string
		$sql = implode(',',$_POST['gejala']);
		// menampilkan kode gejala yang di pilih
		echo $sql.'<br/>';
		empty($daftar_penyakit);
		empty($daftar_cf);

		if ($sql != 0) {
			// mengambil data dari tabel pengetahuan
			$minta = $crud->joinGetPengetahuan($sql);

			// mendapatkan nama penyakit dan daftar Penyakit
			$id_penyakit_terbesar = '';
			$nama_penyakit_terbesar = '';
			$c = 0;
			foreach ($minta as $hs)
			{
				//memproses id penyakit satu persatu
				$id_penyakit = $hs['id_penyakit'];
				$nama_penyakit = $hs['nama_penyakit'];
				$daftar_penyakit[$c] = $hs['id_penyakit'];
				// menampilkan data penyakit
				echo "<br/>Proses Penyakit ".$daftar_penyakit[$c].".".$nama_penyakit."<br/>==============<br/>";
				//mencari gejala yang mempunyai id penyakit tersebut, agar bisa menghitung CF dari MB dan MD nya
				$m = $crud->getPengetahuan(2);
				print_r($m);
				exit();

				// untuk menghitung gejala pada tabel pengetahuan
				$jml = count($m);
				//jika gejalanya 1 langsung ketemu CF nya
				echo "jml gejala = ".$jml."<br/>";
				// menghitung gejala di tabel pengetahuan
				if ($jml == 1)
				{
					$mb = $h['mb'];
					$md = $h['md'];
					$cf = $mb - $md;
					$daftar_cf[$c] = $cf;
					//cek apakah penyakit ini adalah penyakit dgn CF terbesar ?
					if (($id_penyakit_terbesar == '') || ($cf_terbesar < $cf))
					{
						$cf_terbesar = $cf;
						$id_penyakit_terbesar = $id_penyakit;
						$nama_penyakit_terbesar = $nama_penyakit;
					}
					echo "<br/>proses 1<br/>------------------------<br/>";
					echo "mb = ".$mb."<br/>";
					echo "md = ".$md."<br/>";
					echo "cf = mb - md = ".$mb." - ".$md." = ".$cf."<br/><br/><br/>";
				}
				//jika gejala lebih dari satu harus diproses semua gejala
				else if ($jml > 1)
				{
					$i = 1;
					//proses gejala satu persatu
					foreach($m as $h)
					{
						echo "<br/>proses ".$i."<br/>------------------------------------<br/>";
						//pada gejala yang pertama masukkan MB dan MD menjadi MBlama dan MDlama
						if ($i == 1)
						{
							$mblama = $h['mb'];
							$mdlama = $h['md'];
							echo "mblama = ".$mblama."<br/>";
							echo "mdlama = ".$mdlama."<br/>";
						}
						//pada gejala yang nomor dua masukkan MB dan MD menjadi MBbaru dan MB baru kemudian hitung MBsementara dan MDsementara
						else if ($i == 2)
						{
							$mbbaru = $h['mb'];
							$mdbaru = $h['md'];
							echo "mbbaru = ".$mbbaru."<br/>";
							echo "mdbaru = ".$mdbaru."<br/>";
							$mbsementara = $mblama + ($mbbaru * (1 - $mblama));
							$mdsementara = $mdlama + ($mdbaru * (1 - $mdlama));
							echo "mbsementara = mblama + (mbbaru * (1 - mblama)) = $mblama + ($mbbaru * (1 - $mblama)) = ".$mbsementara."<br/>";
							echo "mdsementara = mdlama + (mdbaru * (1 - mdlama)) = $mdlama + ($mdbaru * (1 - $mdlama)) = ".$mdsementara."<br/>";
							//jika jumlah gejala cuma dua maka CF ketemu
							if ($jml == 2)
							{
								$mb = $mbsementara;
								$md = $mdsementara;
								$cf = $mb - $md;
								echo "mb = mbsementara = ".$mb."<br/>";
								echo "md = mdsementara = ".$md."<br/>";
								echo "cf = mb - md = ".$mb." - ".$md." = ".$cf."<br/><br/><br/>";
								$daftar_cf[$c] = $cf;
								//cek apakah penyakit ini adalah penyakit dgn CF terbesar ?
								if (($id_penyakit_terbesar == '') || ($cf_terbesar < $cf))
								{
									$cf_terbesar = $cf;
									$id_penyakit_terbesar = $id_penyakit;
									$nama_penyakit_terbesar = $nama_penyakit;
								}
							}
						}
						//pada gejala yang ke 3 dst proses MBsementara dan MDsementara menjadi MBlama dan MDlama
						//MB dan MD menjadi MBbaru dan MDbaru
						//hitung MBsementara dan MD sementara yg sekarang
						else if ($i >= 3)
						{
							$mblama = $mbsementara;
							$mdlama = $mdsementara;
							echo "mblama = mbsementara = ".$mblama."<br/>";
							echo "mdlama = mdsementara = ".$mdlama."<br/>";
							$mbbaru = $h['mb'];
							$mdbaru = $h['md'];
							echo "mbbaru = ".$mbbaru."<br/>";
							echo "mdbaru = ".$mdbaru."<br/>";
							$mbsementara = $mblama + ($mbbaru * (1 - $mblama));
							$mdsementara = $mdlama + ($mdbaru * (1 - $mdlama));
							echo "mbsementara = mblama + (mbbaru * (1 - mblama)) = $mblama + ($mbbaru * (1 - $mblama)) = ".$mbsementara."<br/>";
							echo "mdsementara = mdlama + (mdbaru * (1 - mdlama)) = $mdlama + ($mdbaru * (1 - $mdlama)) = ".$mdsementara."<br/>";
							//jika ini adalah gejala terakhir berarti CF ketemu
							if ($jml == $i)
							{
								$mb = $mbsementara;
								$md = $mdsementara;
								$cf = $mb - $md;
								echo "mb = mbsementara = ".$mb."<br/>";
								echo "md = mdsementara = ".$md."<br/>";
								echo "cf = mb - md = ".$mb." - ".$md." = ".$cf."<br/><br/><br/>";
								$daftar_cf[$c] = $cf;
								//cek apakah penyakit ini adalah penyakit dgn CF terbesar ?
								if (($id_penyakit_terbesar == '') || ($cf_terbesar < $cf))
								{
									$cf_terbesar = $cf;
									$id_penyakit_terbesar = $id_penyakit;
									$nama_penyakit_terbesar = $nama_penyakit;
								}
							}
						}
						$i++;
					}
				}
				// variabel c ditambah 1
				$c++;
			}
		}
		// menampilkan id penyakit dan nama penyakit
		echo "penyakit terbesar = ".$id_penyakit_terbesar.".".$nama_penyakit_terbesar."<br/>";
	 ?>
		// menampilkan hasil kemungkinan terkena penyakit
		<table border="0" cellspacing="0" cellpadding="0" width="605">
		<tr>
		<td width="605" class="pageName" align="center"><p>Hasil konsultasi</p></td>
		</tr>

		<tr>
		<td class="bodyText">
		<p align="justify">
		<table width="423" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor=""#BFD0EA"" class="tb_admin">
			<tr>
				<td width="33%" valign="top">Gejala yang dipilih</td>
				<td width="63%"><strong>
				<?php
					// mengambil data tabel gejala
					$getGejala = $crud->getGejala($sql);

					foreach ($getGejala as $value) {
						$namaPenyakit[] = $value['nama_gejala'];
					}

					echo implode(',',$namaPenyakit)
				?>
				</strong></td>
			</tr>

			<tr>
			  <td valign="top">&nbsp;</td>
			  <td>&nbsp;</td>
			  </tr>
			<tr>
			  <td>Daftar penyakit </td>
			  <td>CF</td>
			  </tr>
			<?php
				$getPenyakit = implode(',',$daftar_penyakit);
				$tbPenyakit = $crud->getPenyakit($getPenyakit);

				$i = 0;
				foreach ($tbPenyakit as $key) {
					echo "
						<tr>
							<td>".$key['nama_penyakit']."</td>
							<td>".$daftar_cf[$i]."</td>
						</tr>
					";
					$i++;
				}
			?>
			<tr>
			  <td valign="top">&nbsp;</td>
			  <td>&nbsp;</td>
			  </tr>
			<tr>
				<td valign="top">Kemungkinan Terbesar Terkena Penyakit </td>
				<?php
				$hs = $crud->getPenyakit($id_penyakit_terbesar);
				//$id_penyakit_terbesar
				?>
				<td><strong><?php echo $hs[0]['nama_penyakit']; ?> </strong></td>
			</tr>
			<tr>
              <td>CF</td>
			  <td><strong><?php echo $cf_terbesar; ?></strong></td>
			  </tr>

			<tr>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  </tr>
		</table>
		</p>
		</td>
		</tr>
		</table>
	<?php
		// asdad
	}
?>

</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="47%" height="35" align="left"><strong>&copy; 2014 ContohProgram.com</strong></td>
        <td width="53%" height="35" align="right"><strong><a href="http://contohprogram.com" target="_blank">Kontak</a> | <a href="http://contohprogram.com/cf-php-mysql-source-code.php" target="_blank">About</a><a href="http://contohprogram.com/wp-php-mysql.php" target="_blank"></a></strong></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
