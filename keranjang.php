<?php
session_start();
include 'koneksi/koneksi.php';

// Fungsi untuk menambahkan produk ke keranjang
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $product_id = $data['id_produk'];

    // Cek apakah produk sudah ada di keranjang
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $found = false;
    foreach ($_SESSION['cart'] as $key => $cart_item) {
        if ($cart_item['id_produk'] == $product_id) {
            $_SESSION['cart'][$key]['quantity'] += 1;
            $found = true;
            break;
        }
    }

    if (!$found) {
        // Ambil data produk dari database
        $query = "SELECT * FROM produk WHERE id_produk = $product_id";
        $result = mysqli_query($koneksi, $query);
        $product = mysqli_fetch_assoc($result);

        // Tambahkan produk ke keranjang
        $_SESSION['cart'][] = [
            'id_produk' => $product['id_produk'],
            'nama_produk' => $product['nama_produk'],
            'harga' => $product['harga'],
            'quantity' => 1,
            'gambar' => $product['gambar']
        ];
    }

    echo json_encode(['success' => true, 'message' => 'Produk berhasil ditambahkan ke keranjang']);
}

// Menampilkan keranjang belanja
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    $total_price = 0;
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
    <title>Mom's Cemara</title>
    <style>
      .no-scrollbar::-webkit-scrollbar {
        display: none;
      }
      .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
      }

      .wave-bg-1 {
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="1440" height="600" viewBox="0 0 1440 600" fill="none"><path d="M1440 600H0.124157C0.124157 544.093 0.124059 202.017 0 143.434C198.57 167.744 357.089 186.04 591.833 143.434C1010.19 67.503 1440 0 1440 0V600Z" fill="%23FFC9DB"/></svg>')
          no-repeat center;
        background-size: cover;
      }

      .wave-bg-2 {
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="1440" height="146" viewBox="0 0 1440 146" fill="none"><path d="M0 20.8938L48.4195 10.7718C124.756 -5.18625 203.768 -3.04866 279.13 17.0134L316.327 26.9154C383.493 44.7958 454.358 43.2253 520.666 22.3868V22.3868C587.959 1.23882 659.923 -0.0582481 727.934 18.651L777.877 32.3898C830.591 46.8909 885.327 52.6482 939.905 49.4324L1027.47 44.2732C1058.75 42.4298 1089.8 37.649 1120.19 29.994L1154.68 21.3058C1236.24 0.761541 1321.67 1.12426 1403.05 22.3604L1440 32.002V141.016L0 145.502V20.8938Z" fill="%23FFB9D1"/></svg>')
          no-repeat center;
        background-size: cover;
      }

      .wave-bg-3 {
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="1440" height="149" viewBox="0 0 1440 149" fill="none"><defs><linearGradient id="paint0_linear_283_1184" x1="720" y1="0" x2="720" y2="149" gradientUnits="userSpaceOnUse"><stop stop-color="%23FFE0EB"/><stop offset="1" stop-color="%23FFB9D1"/></linearGradient></defs><path d="M0 0.501953L98.0261 20.8944C159.266 33.6343 222.553 32.8175 283.444 18.5013V18.5013C334.059 6.60103 386.417 4.00843 437.96 10.8501L476.714 15.9942C521.366 21.9213 566.615 21.7873 611.232 15.5958L637.298 11.9786C692.011 4.38604 747.63 6.42342 801.641 17.9986V17.9986C867.779 32.1729 936.187 32.0156 1002.26 17.5372L1004.18 17.1172C1054.4 6.11109 1106.07 3.20159 1157.21 8.49877L1208.13 13.7728C1242.29 17.3103 1276.71 17.32 1310.87 13.802L1440 0.501953V148.502H0V0.501953Z" fill="url(%23paint0_linear_283_1184)"/></svg>')
          no-repeat center;
        background-size: cover;
      }
    </style>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        document
          .getElementById("btn-selengkapnya")
          .addEventListener("click", function () {
            Swal.fire({
              title:
                '<h2 class="text-xl font-bold">Tentang <span class="text-pink-500">Mom&#39;s Cemara</span></h2>',
              html: `<div class="text-left bg-[#B6EADD]">
                        <p class="mb-4">Moms Cemara adalah sebuah toko kue yang berdedikasi untuk menghadirkan kue-kue berkualitas tinggi dengan cita rasa rumahan yang autentik. Didirikan dengan cinta dan semangat untuk menciptakan kelezatan yang dapat dinikmati oleh seluruh keluarga, Moms Cemara memastikan bahwa setiap produk dibuat dari bahan-bahan terbaik dan resep yang telah teruji, sehingga semua produk yang diproduksi sudah teruji halal.</p>
                        <p>Moms Cemara percaya bahwa kue bukan hanya sekedar makanan, tetapi juga sebuah sarana untuk menyebarkan kebahagiaan dan kehangatan. Oleh karena itu, setiap kue yang dihasilkan tidak hanya enak, tetapi juga dibuat dengan penuh perhatian dan kasih sayang, sehingga setiap gigitan membawa kebahagiaan tersendiri.</p>
                    </div>`,
              showCloseButton: true,
              showConfirmButton: false,
              customClass: {
                popup: "bg-[#B6EADD]",
                title: "text-black",
                htmlContainer: "text-white",
                confirmButton:
                  "bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-4 rounded-xl",
              },
            });
          });
      });

      document.addEventListener("DOMContentLoaded", function () {
        const mobileMenuButton = document.querySelector("button.md\\:hidden");
        const mobileMenu = document.getElementById("mobile-menu");

        mobileMenuButton.addEventListener("click", function () {
          const isHidden = mobileMenu.classList.contains("hidden");
          if (isHidden) {
            mobileMenu.classList.remove("hidden");
          } else {
            mobileMenu.classList.add("hidden");
          }
        });

        // Close menu when clicking outside
        document.addEventListener("click", function (event) {
          if (
            !mobileMenu.contains(event.target) &&
            !mobileMenuButton.contains(event.target)
          ) {
            mobileMenu.classList.add("hidden");
          }
        });
      });

      function aboutus() {
        Swal.fire({
          position: "top-mid",
          icon: "success",
          title: "Nantikan Info Promo Menarik Lainnya",
          showConfirmButton: false,
          timer: 1500,
          customClass: {
            popup: "bg-[#FFE0EB]",
            title: "text-black",
            htmlContainer: "text-white",
            confirmButton:
              "bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-4 rounded-xl",
          },
        });
      }
    </script>
  </head>
  <body>
    <!-- Navbar -->
    <nav
      class="flex items-center justify-between px-4 md:px-8 lg:px-12 py-4 bg-gray-300/50 backdrop-blur-md fixed w-full z-50"
    >
      <div class="flex items-center">
        <img
          src="assets/foto/logo.png"
          alt="logo"
          class="w-8 h-8 md:w-10 md:h-10 lg:w-12 lg:h-12 rounded-full"
        />
        <span class="ml-2 text-sm md:text-base lg:text-lg font-bold text-white"
          >MOM'S CEMARA</span
        >
      </div>

      <!-- Desktop Menu -->
      <div
        class="hidden md:flex items-center space-x-4 lg:space-x-6 text-white"
      >
        <a href="keranjang_belanja.html"
          ><button>
            <img
              src="assets/foto/Shopping Bag.png"
              alt="Shopping Bag"
              class="w-6 h-6 lg:w-7 lg:h-7"
            /></button
        ></a>
        <a
          href="index_after.html"
          class="hover:text-gray-300 text-sm lg:text-base"
          >Beranda</a
        >
        <a
          href="index_after.html"
          class="hover:text-gray-300 text-sm lg:text-base"
          >Menu</a
        >
        <a
          href="index_after.html"
          class="hover:text-gray-300 text-sm lg:text-base"
          >Promo</a
        >
        <a href="profile_user.html">
          <button
            class="px-3 py-1.5 lg:px-4 lg:py-2 rounded-lg hover:bg-white hover:text-gray-800 transition text-sm lg:text-base"
          >
            <img
              src="assets/foto/avatar.png"
              alt=""
              class="w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-full object-cover"
            />
          </button>
        </a>
      </div>

      <!-- Mobile Menu Button -->
      <button class="md:hidden text-white">
        <svg
          class="w-6 h-6"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16"
          ></path>
        </svg>
      </button>

      <!-- Mobile Menu -->
      <div
        id="mobile-menu"
        class="hidden md:hidden fixed top-16 right-0 h-screen w-64 bg-gray-300/50 backdrop-blur-md p-4 z-40 transform transition-transform duration-300"
      >
        <div class="flex flex-col space-y-4 text-white">
          <a
            href="keranjang_belanja.html"
            class="flex justify-center hover:text-black"
          >
            <img
              src="assets/foto/Shopping Bag.png"
              alt="Shopping Bag"
              class="w-6 h-6"
            />
          </a>
          <a href="index_after.html" class="hover:text-black text-center"
            >Beranda</a
          >
          <a href="index_after.html" class="hover:text-black text-center"
            >Menu</a
          >
          <a href="index_after.html" class="hover:text-black text-center"
            >Promo</a
          >
          <a href="profile_user.html" class="hover:text-black text-center">
            <div class="flex justify-center items-center gap-2">
              <a href="profile_user.html" class="flex items-center gap-2"
                ><img
                  src="assets/foto/avatar.png"
                  alt=""
                  class="w-8 h-8 rounded-full object-cover"
                />
                <span class="text-sm">Lihat Profil</span></a
              >
            </div>
          </a>
        </div>
      </div>
    </nav>
    <!-- Hero Section -->
    <section
      class="bg-[#B6EADD] min-h-screen flex flex-col justify-center items-center pt-20 pb-20 px-4 sm:px-6 lg:px-8"
    >
      <div class="w-full max-w-md md:max-w-3xl mb-6 flex flex-row items-center">
        <img src="assets/foto/tombol_balik.png" alt="backing" class="mr-2 mt-2" />
        <h1 class="text-3xl sm:text-3xl font-bold text-gray-800">
          Keranjang Belanja
        </h1>
      </div>
      <div
        class="bg-pink-100 w-full max-w-md md:max-w-3xl rounded-lg shadow-lg p-4 sm:p-6"
      >
        <!-- Shopping Cart Items -->
        <?php if (count($cart_items) > 0): ?>
        <div class="space-y-4">
            <?php foreach ($cart_items as $key => $item): 
                $total_item_price = $item['harga'] * $item['quantity'];
                $total_price += $total_item_price;
            ?>
                <div class="flex flex-row sm:flex-row items-center border-b-2 border-black pb-4">
                    <img src="assets/foto_produk/<?php echo $item['gambar']; ?>" alt="<?php echo $item['nama_produk']; ?>" class="w-22 h-20 sm:w-18 h-20 rounded-md object-cover mb-3 sm:mb-0" />
                    <div class="ml-2 sm:ml-4 flex-1 text-left sm:text-left">
                        <h2 class="font-semibold text-gray-700 mb-2"><?php echo $item['nama_produk']; ?></h2>
                        <div class="flex items-center justify-start sm:justify-start">
                            <button class="text-pink-500 border border-pink-500 rounded-full w-6 h-6 flex items-center justify-center hover:bg-pink-500 hover:text-white transition-colors add-quantity" data-key="<?php echo $key; ?>">−</button>
                            <span class="mx-2 text-gray-800"><?php echo $item['quantity']; ?></span>
                            <button class="text-pink-500 border border-pink-500 rounded-full w-6 h-6 flex items-center justify-center hover:bg-pink-500 hover:text-white transition-colors reduce-quantity" data-key="<?php echo $key; ?>">+</button>
                        </div>
                    </div>
                    <p class="text-black font-bold mt-3 sm:mt-0">Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></p>
                </div>
            <?php endforeach; ?>
            
            <!-- Notes Section -->
            <div class="mt-4">
                <textarea id="notes" rows="3" class="w-full p-2 border rounded-md focus:ring focus:ring-pink-300 text-sm sm:text-base" placeholder="Catatan (maksimal 150 kata)"></textarea>
            </div>

            <!-- Subtotal and Button -->
            <div class="flex flex-row sm:flex-row justify-between items-start sm:items-center mt-6 gap-4 sm:gap-0">
                <p class="text-base sm:text-lg font-bold text-gray-700">Subtotal Produk</p>
                <p class="text-base sm:text-lg font-bold text-gray-800">Rp <?php echo number_format($total_price, 0, ',', '.'); ?></p>
            </div>

            <!-- Checkout Button -->
            <a href="informasi_pemesanan.html">
                <button class="w-auto sm:w-auto mt-4 py-2 px-3 bg-teal-500 hover:bg-teal-600 text-white rounded-md font-semibold shadow-lg text-sm sm:text-base max-w-md md:max-w-3xl">
                    Lanjutkan Pemesanan
                </button>
            </a>
        </div>
    <?php else: ?>
        <p class="text-center text-lg">Keranjang Anda kosong.</p>
    <?php endif; ?>
</div>

    </section>

    <script>
            document.querySelectorAll('.add-quantity').forEach(button => {
                button.addEventListener('click', async () => {
                    const key = button.dataset.key;
                    const response = await fetch('keranjang.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ action: 'add_quantity', key: key })
                    });
                    const result = await response.json();
                    location.reload();
                });
            });

            document.querySelectorAll('.reduce-quantity').forEach(button => {
                button.addEventListener('click', async () => {
                    const key = button.dataset.key;
                    const response = await fetch('keranjang.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ action: 'reduce_quantity', key: key })
                    });
                    const result = await response.json();
                    location.reload();
                });
            });

            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', async () => {
                    const key = button.dataset.key;
                    const response = await fetch('keranjang.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ action: 'remove_item', key: key })
                    });
                    const result = await response.json();
                    location.reload();
                });
            });
        </script>

    <div class="wave-bg-3 relative bg-[#FFE0EB]">
      <div class="absolute w-full h-36 top-0"></div>
      <div
        class="max-w-screen-2xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 p-8 relative z-10"
      >
        <!-- Section Tentang Kami -->
        <div class="text-left text-gray-800 py-10">
          <h2 class="text-lg font-semibold mb-2">Tentang Kami</h2>
          <p class="text-sm">
            Jl. Tipar<br />
            RT. 05 RW. 07 No. 15<br />
            Pekayon, Pasar Rebo, Jakarta Timur
          </p>
        </div>

        <!-- Section Subscribe -->
        <div class="text-left py-10">
          <p class="text-sm mb-4 font-bold">
            Subscribe untuk mendapatkan informasi <br />promo terbaru tentang
            produk Mom's Cemara
          </p>
          <div
            class="flex flex-col sm:flex-row gap-2 sm:gap-4 justify-start items-center"
          >
            <input
              type="email"
              placeholder="Enter your email Address"
              class="px-4 py-2 w-2/3 shadow-xl md:w-1/2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-500"
            />
            <button onclick="aboutus()"
              class="px-4 py-2 bg-teal-500 text-white rounded-lg shadow-xl hover:bg-teal-600"
            >
              Subscribe
            </button>
          </div>
        </div>

        <!-- Section Logo dan Sosial Media -->
        <div class="text-center md:text-right p-10">
          <img
            src="assets/foto/logo.png"
            alt="Mom's Cemara Logo"
            class="w-24 h-24 mx-auto rounded-lg shadow-lg md:ml-auto mb-4"
          />
          <div
            class="flex justify-center md:justify-center space-x-4 text-pink-600"
          >
            <a href="#" class="hover:text-pink-800">
              <img
                class="h-10 w-10"
                src="assets/foto/instagram.png"
                alt="instagram"
              />
            </a>
            <a href="#" class="hover:text-pink-800">
              <img class="h-10 w-10" src="assets/foto/whatsapp.png" alt="whatsapp" />
            </a>
            <a href="#" class="hover:text-pink-800">
              <img class="h-10 w-10" src="assets/foto/mail.png" alt="mail" />
            </a>
          </div>
        </div>
      </div>
      <!-- Footer -->
      <div class="wave-bg-1 relative -mt-1">
        <div
          class="wave-bg-2 absolute w-full h-20 top-0 flex items-center justify-center"
        >
          <span class="text-gray-600 text-sm">© 2024 All Rights Reserved</span>
        </div>
        <div
          class="max-w-screen-xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 p-8 relative z-10"
        ></div>
      </div>
    </div>
  </body>
</html>
<?php
}
?>
