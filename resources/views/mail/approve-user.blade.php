<p>Yth {{ $nama }},</p>

<p>Di informasikan bahwa akun anda @if($status == 'Rejected') <b>gagal</b> @else <b>berhasil</b> @endif melakukan registrasi pada aplikasi IBR (Indonesia Basketball Referee) dengan data sebagai berikut :</p>

<p>Username : {{ $username }}</p>

<p>Nama : {{ $nama }}</p>

<p>Nomor Lisensi : {{ $no_lisensi }}</p>

@if($status == 'Rejected')

    <p>Tanggal Ditolak : {{ date('d-m-Y', strtotime($tanggal_approve)) }}</p>

@else

    <p>Tanggal Diterima : {{ date('d-m-Y', strtotime($tanggal_approve)) }}</p>

@endif

<p>Untuk detail informasi dapat di akses melalui aplikasi inabasketballreferee.com</p>

<p>Mohon agar tidak membalas email ini karena email ini di buat secara otomatis dari aplikasi IBR (Indonesia Basketball Referee)</p>