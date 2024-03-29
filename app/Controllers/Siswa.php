<?php

namespace App\Controllers;

use App\Config\Session;
use Riyu\Http\Request;
use Utils\Flasher;
use Riyu\Validation\Validation;
use App\Models\Siswa as ModelsSiswa;
use App\Models\User;
use Exception;

class Siswa extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . base_url . 'auth/login');
            exit();
        }
        if (Session::get('type') == "guru") {
            header('Location: ' . base_url . 'dashboard/guru');
            exit();
        }
    }

    public function index()
    {
        try {
            header('Location: ' . base_url . 'siswa/page/1');
            exit();
            // $data['title'] = "Siswa";
            // $data['jumlah_data'] = ModelsSiswa::where('status', '1')->count();
            // $data['data_siswa'] = ModelsSiswa::where('status', '1')->orderby('nama_siswa', 'asc')->all();
            // $data['admin'] = User::where('id_admin', Session::get('user'))->first();
            // return view(['templates/header', 'templates/sidebar', 'siswa/index', 'templates/footer'], $data);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode(), $th->getPrevious());
        }
    }

    public function pagination(Request $request)
    {
        try {
            $data['title'] = "Siswa";
            $data['jumlah_data'] = ModelsSiswa::where('status', '1')->count();
            $data['data_siswa'] = ModelsSiswa::where('status', '1')->orderby('nama_siswa', 'asc')->paginate($request->page);
            $data['admin'] = User::where('id_admin', Session::get('user'))->first();
            $data['halaman_aktif'] = $request->page;
            $data['jumlah_halaman'] = ceil($data['jumlah_data'] / 10);
            $data['halaman_akhir'] = $data['jumlah_halaman'];
            return view(['templates/header', 'templates/sidebar', 'siswa/index', 'templates/footer'], $data);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode(), $th->getPrevious());
        }
    }

    public function tambah()
    {
        $data['title'] = "Siswa";
        $data['data_siswa'] = ModelsSiswa::where('status', '1')->all();
        $data['admin'] = User::where('id_admin', Session::get('user'))->first();
        return view(['templates/header', 'templates/sidebar', 'siswa/tambah', 'templates/footer'], $data);
    }

    public function insert(Request $request)
    {
        // try {
        $data['check'] = ModelsSiswa::where('nis', $request->nis)->first();
        if (isset($data['check']->nis)) {
            Flasher::setFlash('NIS SUDAH DIPAKAI! ' . $data['check']->nis, 'danger');
            header('location: ' . base_url . 'siswa/tambah');
            exit();
        }
        $rule = [
            'nis' => 'required|min:1|max:15',
            'nama' => 'required|min:3|max:120',
            'alamat' => 'required|min:10|max:120',
            'telepon' => 'required|min:10|max:14',
            'jk' => 'required',
            'tempat_lahir' => 'required|max:25',
        ];
        $messages = [
            'required' => 'Kolom :field harus diisi!',
            'min' => 'Kolom :field minimal :min karakter!',
            'max' => 'Kolom :field maksimal :max karakter!',
            'numeric' => 'Kolom :field harus berisikan angka!',
        ];
        $errors = Validation::message($request->all(), $rule, $messages);
        $errors = Validation::first($errors);
        // $errors = Validation::make($request->all(), [
        //     'nis' => 'required|min:4|max:15',
        //     'nama' => 'required|min:3|max:120',
        //     'alamat' => 'required|min:10|max:120',
        //     'telepon' => 'required|min:10|max:14',
        //     'jk' => 'required',
        //     'tempat_lahir' => 'required|max:25',
        // ]);
        $valiadatechars = [
            'nis' => htmlspecialchars($request->nis),
            'nama' => htmlspecialchars($request->nama),
            'alamat' => htmlspecialchars($request->alamat),
            'telepon' => htmlspecialchars($request->telepon),
            'jk' => htmlspecialchars($request->jk),
            'tempat_lahir' => htmlspecialchars($request->tempat_lahir),
        ];
        if ($errors) {
            Flasher::setFlash('Gagal Ditambahkan! ' . $errors, 'danger');
            header('location: ' . base_url . 'siswa/tambah');
            exit();
        }

        $phone = $valiadatechars['telepon'];
        // echo $phone;
        if (!is_numeric($phone)) {
            Flasher::setFlash('Gagal Ditambahkan! Nomor Telepon harus berisikan angka!', 'danger');
            header('location: ' . base_url . 'siswa/tambah');
            exit();
        }

        ModelsSiswa::insert([
            'nis' => $valiadatechars['nis'],
            'nama_siswa' => $valiadatechars['nama'],
            'alamat_siswa' => $valiadatechars['alamat'],
            'notelp_siswa' => $phone,
            'jenis_kelamin' => $valiadatechars['jk'],
            'tempat_lahir' => $valiadatechars['tempat_lahir'],
            'tanggal_lahir' => $request->tgl_lahir,
            'status' => 1,
            'password' => password_hash($valiadatechars['nis'], PASSWORD_BCRYPT)
        ])->save();
        Flasher::setFlash('Berhasil Ditambahkan', 'success');
        header('location: ' . base_url . 'siswa/tambah');
        exit();
    }

    public function ubah(Request $request)
    {
        $data['title'] = 'Siswa';
        $data['siswa'] = ModelsSiswa::where('nis', $request->nis)->first();
        $data['admin'] = User::where('id_admin', Session::get('user'))->first();
        if ($data['siswa'] == null) {
            header('Location: ' . base_url . 'siswa');
            exit();
        }
        return view(['templates/header', 'templates/sidebar', 'siswa/ubah', 'templates/footer'], $data);
    }

    public function update(Request $request)
    {
        $errors = Validation::make($request->all(), [
            'nis' => 'required|min:1|max:15',
            'nama' => 'required|min:3|max:120',
            'alamat' => 'required|min:10|max:120',
            'telepon' => 'required|min:10|max:14',
            'jk' => 'required',
            'tempat_lahir' => 'required|max:25',
        ]);
        $valiadatechars = [
            'nis' => htmlspecialchars($request->nis),
            'nama' => htmlspecialchars($request->nama),
            'alamat' => htmlspecialchars($request->alamat),
            'telepon' => htmlspecialchars($request->telepon),
            'jk' => htmlspecialchars($request->jk),
            'tempat_lahir' => htmlspecialchars($request->tempat_lahir),
        ];
        // $errors = Validation::first($errors);
        if ($errors) {
            Flasher::setFlash('Gagal Diubah!', 'danger');
            header('location: ' . base_url . 'siswa/ubah/' . $request->nis);
            exit();
        }
        $phone = $valiadatechars['telepon'];
        // echo $phone;
        if (!is_numeric($phone)) {
            Flasher::setFlash('Gagal Diubah! Nomor Telepon harus berisikan angka!', 'danger');
            header('location: ' . base_url . 'siswa/ubah' . $request->nis);
            exit();
        }

        ModelsSiswa::update([
            'nis' => $valiadatechars['nis'],
            'nama_siswa' => $valiadatechars['nama'],
            'alamat_siswa' => $valiadatechars['alamat'],
            'notelp_siswa' => $phone,
            'jenis_kelamin' => $valiadatechars['jk'],
            'tempat_lahir' => $valiadatechars['tempat_lahir'],
            'tanggal_lahir' => $request->tgl_lahir,
            'status' => 1
        ])->where('nis', $request->nis)->save();
        Flasher::setFlash('Berhasil Diubah', 'success');
        header('location: ' . base_url . 'siswa');
        exit();
    }
    public function delete(Request $request)
    {
        if (!isset($_POST['submit_hapus'])) {
            header('Location: ' . base_url . 'siswa');
            exit();
        }
        ModelsSiswa::update([
            'status' => 0,
        ])->where('nis', $request->nis)->save();
        Flasher::setFlash('Berhasil Dihapus', 'success');
        header('Location: ' . base_url . 'siswa');
        exit();
    }

    public function cari(Request $request)
    {
        if ($request->q == null || "") {
            Flasher::setFlash('Masukkan keyword pencarian siswa!', 'danger');
            header("Location: " . base_url . 'siswa');
            exit();
        }
        $data['title'] = 'Hasil Pencarian Siswa';
        try {
            $keyword = $request->q;
            $data['keyword'] = $keyword;
            $data['jumlah_data'] = ModelsSiswa::where('status', '1')
                ->where('nama_siswa', 'LIKE', '%' . $keyword . '%')
                ->orWhere('nis', 'LIKE', '%' . $keyword . '%')
                ->count();
            $data['data_siswa'] = ModelsSiswa::where('status', '1')
                ->where('nama_siswa', 'LIKE', '%' . $keyword . '%')
                ->orWhere('nis', 'LIKE', '%' . $keyword . '%')
                ->orderby('nama_siswa', 'asc')->paginate($request->page);
            $data['admin'] = User::where('id_admin', Session::get('user'))->first();
            $data['halaman_aktif'] = $request->page;
            $data['jumlah_halaman'] = ceil($data['jumlah_data'] / 10);
            $data['halaman_akhir'] = $data['jumlah_halaman'];
            return view(['templates/header', 'templates/sidebar', 'siswa/cari/index', 'templates/footer'], $data);
        } catch (\Throwable $th) {
            throw new \riyu\Helpers\Errors\AppException($th->getMessage(), $th->getCode(), $th->getPrevious());
        }

        // $data['data_siswa'] = ModelsSiswa::where('status', '1')
        //     ->where('nama_siswa', 'LIKE', '%' . $request->keyword . '%')
        //     ->orWhere('nis', 'LIKE', '%' . $request->keyword . '%')
        //     ->orderby('nama_siswa', 'asc')->all();
        // $data['admin'] = User::where('id_admin', Session::get('user'))->first();
        // return view(['templates/header', 'templates/sidebar', 'siswa/index', 'templates/footer'], $data);
    }

    public function aksiCari(Request $request)
    {
        if ($request->keyword == null) {
            Flasher::setFlash('Masukkan Keyword pencarian', 'danger');
            header('Location: ' . base_url . 'siswa/page/1');
            exit();
        }
        // echo $request->keyword;
        header('Location: ' . base_url . 'siswa/cari/' . trim($request->keyword) . '/page/1');
        exit();
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;

        if (isset($request->page)) {
            $this->cari($request);
        } else {
            header('Location: ' . base_url . 'siswa/cari?q=' . $keyword . '&page=1');
            exit();
        }
    }
}