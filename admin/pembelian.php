<?php
include '../koneksi/koneksi.php'; // Menyertakan file koneksi
$ambil = $koneksi->query("SELECT * FROM pembelian JOIN pelanggan ON pembelian.id_pelanggan=pelanggan.id_pelanggan");
$pembelian = array(); // Initialize the pembelian variable as an empty array

while ($pecah = $ambil->fetch_assoc()) {
    $pembelian[] = $pecah; // Populate the pembelian array with the results from the query
}
?>

<main class="container mt-4">
    <div class="shadow p-3 mb-3 bg-white rounded">
        <h5><b>Pembelian</b></h5>
    </div>

    <div class="card shadow bg-white">
        <div class="card-body">
            <table class="table table-bordered table-hover table-striped" id="tables">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th width="150">Tanggal</th>
                        <th width="150">Total</th>
                        <th width="2000">Status</th>
                        <th>Resi</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pembelian as $key => $value): ?>
                    <tr>
                        <td width="50"><?php echo $key+1; ?></td>
                        <td><?php echo $value['nama_pelanggan']; ?></td>
                        <td><?php echo $value['email']; ?></td>
                        <td width="150"><?php echo date("D, d M Y", strtotime($value['tanggal_pembelian'])); ?></td>
                        <td width="150">Rp <?php echo number_format($value['total_pembelian']); ?></td>
                        <td><?php echo $value['status']; ?></td>
                        <td><?php echo $value['resi']; ?></td>
                        <td class="text-center" width="400">
                            <!-- Trigger Modal -->
                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailModal<?php echo $value['id_pembelian']; ?>">Detail</button>

                            <?php if($value['status'] !== 'pending'): ?>
                                <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#paymentModal<?php echo $value['id_pembelian']; ?>">Lihat Pembayaran</button>
                                <a href="index.php?halaman=hapus_pembelian&id=<?php echo $value['id_pembelian']; ?>" class="btn btn-sm btn-danger mt-2">Hapus</a>
                            <?php else: ?>
                                <a href="index.php?halaman=hapus_pembelian&id=<?php echo $value['id_pembelian']; ?>" class="btn btn-sm btn-danger">Hapus</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Modal start -->
<?php foreach ($pembelian as $value): ?>
<div class="modal fade" id="detailModal<?php echo $value['id_pembelian']; ?>" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel<?php echo $value['id_pembelian']; ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel<?php echo $value['id_pembelian']; ?>">Detail Pembelian </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Detail Content -->
                <?php
                    $id_pembelian = $value['id_pembelian'];
                    $ambil = $koneksi->query("SELECT * FROM pembelian JOIN pelanggan ON pembelian.id_pelanggan=pelanggan.id_pelanggan WHERE pembelian.id_pembelian='$id_pembelian'");
                    $detail = $ambil->fetch_assoc();
                ?>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>No.Pembelian :</th>
                            <td><?php echo $detail['id_pembelian']; ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal :</th>
                            <td><?php echo $detail['tanggal_pembelian']; ?></td>
                        </tr>
                        <tr>
                            <th>Total :</th>
                            <td>Rp. <?php echo number_format($detail['total_pembelian']); ?></td>
                        </tr>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>Alamat:</th>
                            <td><?php echo $detail['alamat']; ?></td>
                        </tr>
                        <tr>
                            <th>Kota/Kabupaten:</th>
                            <td><?php echo $detail['distrik']; ?></td>
                        </tr>
                        <tr>
                            <th>Provinsi:</th>
                            <td><?php echo $detail['provinsi']; ?></td>
                        </tr>
                        <tr>
                            <th>Ekspedisi:</th>
                            <td><?php echo $detail['ekspedisi']; ?></td>
                        </tr>
                        <tr>
                            <th>Jumlah Ongkir:</th>
                            <td>Rp. <?php echo number_format($detail['ongkir']); ?></td>
                        </tr>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>Nama :</th>
                            <td><?php echo $detail['nama_pelanggan']; ?></td>
                        </tr>
                        <tr>
                            <th>Email :</th>
                            <td><?php echo $detail['email']; ?></td>
                        </tr>
                        <tr>
                            <th>Telepon :</th>
                            <td><?php echo $detail['telepon_pelanggan']; ?></td>
                        </tr>
                    </table>
                </div>

                <?php
                    $pembelian_produk = array();
                    $ambil = $koneksi->query("SELECT * FROM pembelian_produk JOIN produk ON pembelian_produk.id_produk=produk.id_produk WHERE pembelian_produk.id_pembelian='$id_pembelian'");
                    while($pecah = $ambil->fetch_assoc()) {
                        $pembelian_produk[] = $pecah;
                    }
                ?>

                <div class="card shadow bg-white mt-4">
                    <div class="card-body">
                        <table class="table table-bordered table-hover table-striped" id="tables">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pembelian_produk as $key => $value): ?>
                                    <?php $subtotal = $value['harga_produk']*$value['jumlah']; ?>
                                    <tr>
                                        <td><?php echo $key+1; ?></td>
                                        <td><?php echo $value['nama_produk']; ?></td>
                                        <td>Rp <?php echo number_format($value['harga_produk']); ?></td>
                                        <td><?php echo $value['jumlah']; ?></td>
                                        <td>Rp <?php echo number_format($subtotal); ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Modal end -->


<!-- Modal start for payment details -->
<?php foreach ($pembelian as $value): ?>
<div class="modal fade" id="paymentModal<?php echo $value['id_pembelian']; ?>" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel<?php echo $value['id_pembelian']; ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel<?php echo $value['id_pembelian']; ?>">Halaman Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                    $id_pembelian = $value['id_pembelian'];
                    $ambil = $koneksi->query("SELECT * FROM pembayaran WHERE id_pembelian='$id_pembelian'");
                    $pecah = $ambil->fetch_assoc();
                ?>
                <div class="card shadow bg-white">
                    <div class="card-body row">
                        <div class="col-md-8">
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th>Nama</th>
                                        <td><?php echo htmlspecialchars($pecah['nama']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Bank</th>
                                        <td><?php echo htmlspecialchars($pecah['bank']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Jumlah Pembayaran</th>
                                        <td><?php echo htmlspecialchars($pecah['jumlah']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal</th>
                                        <td><?php echo htmlspecialchars($pecah['tanggal']); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <img src="../assets/foto_bukti/<?php echo htmlspecialchars($pecah['bukti']); ?>" width="250" class="img-thumbnail img-responsive">
                        </div>
                    </div>
                </div>

                <form method="post">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nomor Resi Pengiriman :</label>
                        <div class="col-sm-9">
                            <input type="text" name="resi" class="form-control" placeholder="Masukkan Resi" id="resi">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Status :</label>
                        <div class="col-sm-9">
                            <select name="status" class="form-control" id="status">
                                <option selected disabled>Pilih Status</option>
                                <option value="sedang diproses">Sedang diproses</option>
                                <option value="Pembayaran diterima dan sedang diproses">Pembayaran diterima</option>
                                <option value="Produk sedang diproses">Produk sedang diproses</option>
                                <option value="Barang sedang dikirim">Barang sedang dikirim</option>
                                <option value="Dibatalkan">Dibatalkan</option>
                                <option value="pembayaran tidak sesuai">Pembayaran tidak sesuai</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9">
                            <button name="proses" class="btn btn-primary btn-sm">Proses</button>
                        </div>
                    </div>
                </form>

                <?php 
                    if(isset($_POST['proses'])) {
                        $resi = $_POST['resi'];
                        $status = $_POST['status'];

                        if ($status != "pembayaran tidak sesuai") {
                            $koneksi->query("UPDATE pembelian SET resi='$resi', status='$status' WHERE id_pembelian='$id_pembelian'");
                        } else {
                            $koneksi->query("UPDATE pembelian SET resi='', status='$status' WHERE id_pembelian='$id_pembelian'");
                        }

                        echo "<script>alert('Pembelian diupdate');</script>";
                        echo "<script>location='index.php?halaman=pembelian';</script>";
                    }
                ?>
                <script>
                    document.getElementById('status').addEventListener('change', function() {
                        var resiInput = document.getElementById('resi');
                        if (this.value === 'pembayaran tidak sesuai') {
                            resiInput.disabled = true;
                            resiInput.value = '';
                        } else {
                            resiInput.disabled = false;
                        }
                    });
                </script>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
<!-- Modal end -->


<!-- Include Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
