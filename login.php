<?php
session_start();
include 'koneksi/koneksi.php';

// Cek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validasi input
    if (empty($email) || empty($password)) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Email dan kata sandi harus diisi.',
                    confirmButtonColor: '#EF4444'
                });
            });
        </script>";
    } else {
        // Mengecek apakah email ada di database
        $sql = "SELECT * FROM pelanggan WHERE email = '$email'";
        $result = $koneksi->query($sql);

        if ($result->num_rows > 0) {
            // Ambil data user dari database
            $user = $result->fetch_assoc();
            
            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id_user']; // Pastikan ID sesuai
                $_SESSION['user_name'] = $user['nama'];
                $_SESSION['user_email'] = $user['email'];

                // SweetAlert for successful login
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Login berhasil! Selamat datang, " . $user['nama'] . "!',
                            confirmButtonColor: '#10B981'
                        }).then(function() {
                            window.location.href = 'index.php'; // Redirect after closing the alert
                        });
                    });
                </script>";
            } else {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Email atau kata sandi salah.',
                            confirmButtonColor: '#EF4444'
                        });
                    });
                </script>";
            }
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Email tidak terdaftar.',
                        confirmButtonColor: '#EF4444'
                    });
                });
            </script>";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"
    />
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
    <link rel="icon" href="assets/foto/logo.jpg" type="image/png"> 
    <title>Login User</title>
  </head>
  <body>
    <style>
      .swal2-popup {
            background-color: #ffddef !important;
        }
    </style>
    <script>
      document.querySelector('form').addEventListener('submit', function(event) {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        if (email === "" || password === "") {
          event.preventDefault();  // Mencegah form dikirim jika ada kolom kosong
          Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Semua kolom harus diisi.',
            confirmButtonColor: '#EF4444'
          });
        }
      });

      function showForgotPasswordForm() {
        Swal.fire({
          title: "Reset Kata Sandi",
          html: ` 
            <div class="text-left space-y-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="resetEmail" class="w-full p-2 border rounded-md" placeholder="Masukkan email Anda">
            </div>
            <div class="text-left space-y-4">
                <label class="block text-sm font-medium text-gray-700 mt-4 mb-1">Kata Sandi Baru</label>
                <input type="password" id="newPassword" class="w-full p-2 border rounded-md" placeholder="Masukkan kata sandi baru">
            </div>
        `, 
          showCancelButton: true,
          confirmButtonText: "Simpan Perubahan",
          cancelButtonText: "Batal",
          confirmButtonColor: "#10B981",
          cancelButtonColor: "#EF4444",
          showLoaderOnConfirm: true,
          preConfirm: () => {
            const email = document.getElementById("resetEmail").value;
            const newPassword = document.getElementById("newPassword").value;

            if (!email) {
              Swal.showValidationMessage("Email harus diisi");
              return false;
            }
            if (!newPassword) {
              Swal.showValidationMessage("Kata sandi baru harus diisi");
              return false;
            }

            return new Promise((resolve) => {
              setTimeout(() => {
                resolve();
              }, 1000);
            });
          },
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire({
              icon: "success",
              title: "Kata Sandi Telah Diubah!",
              text: "Silakan login dengan kata sandi baru Anda.",
              confirmButtonColor: "#10B981",
            });
          }
        });
      }
    </script>

    <div class="flex flex-col lg:flex-row min-h-screen">
      <!-- Bagian Kiri -->
      <div
        class="hidden lg:flex flex-col items-center justify-center w-full lg:w-1/2 bg-pink-50 p-4 sm:p-6 lg:p-12 transition-all duration-700 transform opacity-0 lg:opacity-100 translate-x-[-100%] lg:translate-x-0"
      >
        <div class="transform transition-transform hover:scale-105">
          <img
            src="assets/foto/logo.png"
            alt="Logo"
            class="w-32 h-32 sm:w-40 sm:h-40 md:w-48 md:h-48 mx-auto rounded-[20%] shadow-lg"
          />
        </div>
        <p
          class="mt-4 sm:mt-6 text-lg sm:text-xl md:text-2xl font-bold text-black text-center leading-relaxed"
        >
          Rasakan Cinta di
          <span class="text-pink-500 animate-pulse">Setiap Gigitan</span>
        </p>
      </div>

      <!-- Bagian Kanan -->
      <div
        class="flex items-center justify-center w-full min-h-screen lg:w-1/2 bg-green-100 p-4 sm:p-6 lg:p-12"
      >
        <div
          class="bg-pink-50 p-6 sm:p-8 rounded-lg shadow-lg w-full max-w-md mx-auto transform transition-all duration-300 hover:shadow-xl"
        >
          <h2
            class="text-xl sm:text-2xl md:text-3xl font-bold text-center text-black mb-6"
          >
            Masuk
          </h2>

          <!-- Form -->
          <form method="POST" action="" class="space-y-4 md:space-y-6">
            <div class="mb-4">
              <div
                class="flex items-center border border-gray-300 rounded-md bg-white hover:border-pink-500 transition-colors duration-300"
              >
                <span class="p-2 text-gray-500">
                  <img src="assets/foto/email.png" alt="email" class="w-9 h-9" />
                </span>
                <input
                  type="email"
                  name="email"
                  id="email"
                  placeholder="Masukkan Email"
                  class="w-full p-2 md:p-3 text-sm md:text-base border-none focus:ring-0 focus:outline-none rounded-md"
                />
              </div>
            </div>

            <div class="mb-4">
              <div
                class="flex items-center border border-gray-300 rounded-md bg-white hover:border-pink-500 transition-colors duration-300"
              >
                <span class="p-2 text-gray-500">
                  <img
                    src="assets/foto/password.png"
                    alt="password"
                    class="w-9 h-9"
                  />
                </span>
                <input
                  type="password"
                  name="password"
                  id="password"
                  placeholder="Masukkan Kata Sandi"
                  class="w-full p-2 md:p-3 text-sm md:text-base border-none focus:ring-0 focus:outline-none rounded-md"
                />
              </div>
            </div>

            <div
              class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 space-y-2 sm:space-y-0"
            >
              <label
                class="flex items-center text-sm md:text-base text-gray-600"
              >
                <input
                  type="checkbox"
                  class="mr-2 border-gray-300 rounded focus:ring-pink-500"
                />
                Ingat Kata Sandi
              </label>
              <a
                onclick="showForgotPasswordForm()"
                class="text-sm md:text-base text-pink-500 hover:text-pink-600 hover:underline transition-colors duration-300 cursor-pointer"
                >Lupa Kata Sandi?</a
              >
            </div>

            <button
              type="submit"
              class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 md:py-3 md:text-lg rounded-md transform transition-all duration-300 hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50"
            >
              Masuk
            </button>
          </form>

          <p class="mt-6 text-center text-sm md:text-base text-gray-600">
            Belum Punya Akun?
            <a
              href="daftar.php"
              class="text-pink-500 font-semibold hover:text-pink-600 hover:underline transition-colors duration-300"
              >Daftar Disini</a
            >
          </p>
        </div>
      </div>
    </div>
  </body>
</html>
