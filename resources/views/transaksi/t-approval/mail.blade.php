<p>Yth {{ $nama }},</p>

<p>Diberitahukan bahwa akun anda @if($status == 'Rejected') <b>gagal</b> @else <b>berhasil</b> @endif di registrasi pada aplikasi IBR (Indonesia Basketball Referee) dengan data sebagai berikut :</p>

<p>Username : {{ $username }}</p>

<p>Nama : {{ $nama }}</p>

<p>Nomor Lisensi : {{ $no_lisensi }}</p>

@if($status == 'Rejected')

    <p>Tanggal Ditolak : {{ date('d-m-Y', strtotime($tanggal_approve)) }}</p>

@else

    <p>Tanggal Diterima : {{ date('d-m-Y', strtotime($tanggal_approve)) }}</p>

@endif

<p>Demikian pemberitahuan kami.</p>