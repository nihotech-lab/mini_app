<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Http\Middleware\AdminAuth;

// ------------------------------------------------------------------
// Shared data
// ------------------------------------------------------------------
$categories = [
    'robotics' => 'ሮቦቲክስ እና ኢኖቬሽን (Robotics & Innovation)',
    'software' => 'ሶፍትዌር ልማት (Software Development)',
    'ai'       => 'ሰው ሰራሽ ልህቀት (AI & Machine Learning)',
    'art'      => 'ዲጂታል ጥበብ እና ዲዛይን (Digital Art & Design)',
];

$headStyles = '
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: "Noto Sans Ethiopic", ui-sans-serif, system-ui; }
        .glow { box-shadow: 0 0 40px rgba(56,189,248,0.25); }
    </style>
';

// ==================================================================
// 1. HOME / LANDING PAGE
// ==================================================================
Route::get('/', function () use ($headStyles) {
    $html = '
    <!DOCTYPE html>
    <html lang="am">
    <head>' . $headStyles . '<title>የቴክኖሎጂ እና ፈጠራ ውድድር</title></head>
    <body class="bg-gradient-to-br from-gray-900 via-slate-900 to-black min-h-screen text-white flex items-center justify-center p-4">
        <div class="max-w-2xl w-full text-center">
            <p class="text-cyan-400 font-semibold tracking-widest mb-3">INNOVATION • ROBOTICS • ART</p>
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4">የቴክኖሎጂ ፈጠራ እና ዲጂታል ጥበብ ውድድር</h1>
            <p class="text-gray-300 mb-10">ሮቦቲክስ፣ ሶፍትዌር፣ AI እና ዲጂታል ጥበብ ፕሮጀክቶችዎን ያስመዝግቡ እና ከሌሎች ፈጣሪዎች ጋር ይወዳደሩ።</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="glow bg-cyan-500 hover:bg-cyan-400 text-black font-bold py-3 px-8 rounded-lg transition">ይመዝገቡ</a>
                <a href="/admin/login" class="border border-gray-600 hover:border-cyan-400 text-gray-300 hover:text-white font-bold py-3 px-8 rounded-lg transition">የአስተዳዳሪ መግቢያ</a>
            </div>
        </div>
    </body>
    </html>';
    return response($html);
});

// ==================================================================
// 2. REGISTRATION FORM (public)
// ==================================================================
Route::get('/register', function () use ($categories, $headStyles) {
    $catOptions = '';
    foreach ($categories as $key => $label) {
        $catOptions .= '<option value="' . $key . '">' . $label . '</option>';
    }

    $html = '
    <!DOCTYPE html>
    <html lang="am">
    <head>' . $headStyles . '<title>የምዝገባ ቅጽ</title></head>
    <body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
        <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-lg">
            <h1 class="text-2xl font-bold mb-1 text-gray-800">ይመዝገቡ</h1>
            <p class="text-sm text-gray-500 mb-6">የቴክኖሎጂ ፈጠራ እና ዲጂታል ጥበብ ውድድር</p>' .
            (session('success') ? '<div class="bg-green-100 text-green-700 p-3 rounded mb-4">' . session('success') . '</div>' : '') . '
            <form action="/register" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="_token" value="' . csrf_token() . '">

                <div>
                    <label class="block text-gray-700 font-bold mb-1">ስም፡</label>
                    <input type="text" name="name" class="w-full border border-gray-300 p-2 rounded" required>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-gray-700 font-bold mb-1">ስልክ፡</label>
                        <input type="text" name="phone" class="w-full border border-gray-300 p-2 rounded" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-1">ኢሜይል (አማራጭ)፡</label>
                        <input type="email" name="email" class="w-full border border-gray-300 p-2 rounded">
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-1">ክፍል፡</label>
                    <input type="text" name="grade" class="w-full border border-gray-300 p-2 rounded" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-1">ዘርፍ (Track)፡</label>
                    <select name="category" class="w-full border border-gray-300 p-2 rounded" required>' . $catOptions . '</select>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-1">የቡድን ስም (አማራጭ)፡</label>
                    <input type="text" name="team_name" class="w-full border border-gray-300 p-2 rounded">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-1">የፕሮጀክት ርዕስ፡</label>
                    <input type="text" name="project_title" class="w-full border border-gray-300 p-2 rounded" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-1">ፕሮጀክት መግለጫ፡</label>
                    <textarea name="projects" class="w-full border border-gray-300 p-2 rounded" rows="3" required></textarea>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-1">ግብ፡</label>
                    <textarea name="goals" class="w-full border border-gray-300 p-2 rounded" rows="2" required></textarea>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-1">የክፍያ ስክሪንሻት (Telebirr)፡</label>
                    <input type="file" name="screenshot" class="w-full border border-gray-300 p-2 rounded" accept="image/*" required>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition">ይመዝገቡ</button>
            </form>
        </div>
    </body>
    </html>';

    return response($html);
});

// ==================================================================
// 3. STORE REGISTRATION
// ==================================================================
Route::post('/register', function (Request $request) use ($categories) {
    $request->validate([
        'name'           => 'required|string|max:255',
        'phone'          => 'required|string|max:50',
        'email'          => 'nullable|email|max:255',
        'grade'          => 'required|string|max:50',
        'category'       => 'required|in:' . implode(',', array_keys($categories)),
        'team_name'      => 'nullable|string|max:255',
        'project_title'  => 'required|string|max:255',
        'projects'       => 'required|string',
        'goals'          => 'required|string',
        'screenshot'     => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $path = $request->file('screenshot')->store('screenshots', 'public');

    Participant::create([
        'name'          => $request->name,
        'phone'         => $request->phone,
        'email'         => $request->email,
        'grade'         => $request->grade,
        'category'      => $request->category,
        'team_name'     => $request->team_name,
        'project_title' => $request->project_title,
        'projects'      => $request->projects,
        'goals'         => $request->goals,
        'screenshot'    => $path,
        'status'        => 'pending',
    ]);

    return redirect()->back()->with('success', 'በተሳካ ሁኔታ ተመዝግበዋል! ውጤትዎ ከተረጋገጠ በኋላ ይገለጻል።');
});

// ==================================================================
// 4. ADMIN LOGIN
// ==================================================================
Route::get('/admin/login', function () use ($headStyles) {
    $html = '
    <!DOCTYPE html>
    <html lang="am">
    <head>' . $headStyles . '<title>Admin Login</title></head>
    <body class="bg-gray-900 flex items-center justify-center min-h-screen p-4">
        <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-sm">
            <h1 class="text-xl font-bold mb-6 text-gray-800">የአስተዳዳሪ መግቢያ</h1>' .
            (session('error') ? '<div class="bg-red-100 text-red-700 p-3 rounded mb-4">' . session('error') . '</div>' : '') . '
            <form action="/admin/login" method="POST" class="space-y-4">
                <input type="hidden" name="_token" value="' . csrf_token() . '">
                <div>
                    <label class="block text-gray-700 font-bold mb-1">የይለፍ ቃል፡</label>
                    <input type="password" name="password" class="w-full border border-gray-300 p-2 rounded" required autofocus>
                </div>
                <button type="submit" class="w-full bg-gray-900 text-white font-bold py-2 px-4 rounded hover:bg-gray-700">ግባ</button>
            </form>
        </div>
    </body>
    </html>';
    return response($html);
});

Route::post('/admin/login', function (Request $request) {
    $request->validate(['password' => 'required|string']);

    if ($request->password === env('ADMIN_PASSWORD', 'admin123')) {
        $request->session()->regenerate();
        session(['is_admin' => true]);
        return redirect('/admin/dashboard');
    }

    return redirect()->back()->with('error', 'የተሳሳተ የይለፍ ቃል።');
});

Route::post('/admin/logout', function (Request $request) {
    $request->session()->forget('is_admin');
    $request->session()->invalidate();
    return redirect('/admin/login');
});

// ==================================================================
// 5. ADMIN AREA (protected)
// ==================================================================
Route::middleware([AdminAuth::class])->prefix('admin')->group(function () use ($categories, $headStyles) {

    $navbar = function ($active) {
        $links = [
            'dashboard'    => ['/admin/dashboard', 'ዳሽቦርድ'],
            'participants' => ['/admin/participants', 'ተሳታፊዎች'],
        ];
        $html = '<nav class="bg-gray-900 text-white px-6 py-4 flex justify-between items-center">
            <span class="font-bold">🚀 Admin Panel</span>
            <div class="flex gap-6 items-center">';
        foreach ($links as $key => [$url, $label]) {
            $cls = $key === $active ? 'text-cyan-400 font-semibold' : 'text-gray-300 hover:text-white';
            $html .= '<a href="' . $url . '" class="' . $cls . '">' . $label . '</a>';
        }
        $html .= '<form action="/admin/logout" method="POST" class="inline">
                <input type="hidden" name="_token" value="' . csrf_token() . '">
                <button type="submit" class="text-red-400 hover:text-red-300">ውጣ</button>
            </form>
        </div></nav>';
        return $html;
    };

    // -------------------- DASHBOARD --------------------
    Route::get('/dashboard', function () use ($categories, $headStyles, $navbar) {
        $total = Participant::count();
        $pending = Participant::where('status', 'pending')->count();
        $approved = Participant::where('status', 'approved')->count();
        $rejected = Participant::where('status', 'rejected')->count();

        $catLabels = [];
        $catCounts = [];
        foreach ($categories as $key => $label) {
            $catLabels[] = $label;
            $catCounts[] = Participant::where('category', $key)->count();
        }

        $html = '
        <!DOCTYPE html>
        <html lang="am">
        <head>' . $headStyles . '<title>Admin Dashboard</title></head>
        <body class="bg-gray-100 min-h-screen">
            ' . $navbar('dashboard') . '
            <div class="max-w-6xl mx-auto p-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">ዳሽቦርድ</h1>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white p-5 rounded-xl shadow"><p class="text-gray-500 text-sm">ጠቅላላ ምዝገባ</p><p class="text-3xl font-bold text-gray-800">' . $total . '</p></div>
                    <div class="bg-white p-5 rounded-xl shadow"><p class="text-gray-500 text-sm">በመጠባበቅ ላይ</p><p class="text-3xl font-bold text-yellow-500">' . $pending . '</p></div>
                    <div class="bg-white p-5 rounded-xl shadow"><p class="text-gray-500 text-sm">የጸደቁ</p><p class="text-3xl font-bold text-green-600">' . $approved . '</p></div>
                    <div class="bg-white p-5 rounded-xl shadow"><p class="text-gray-500 text-sm">ውድቅ የተደረጉ</p><p class="text-3xl font-bold text-red-500">' . $rejected . '</p></div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow">
                    <h2 class="font-bold text-gray-700 mb-4">በዘርፍ የተከፋፈለ ምዝገባ</h2>
                    <canvas id="catChart" height="100"></canvas>
                </div>
            </div>

            <script>
                new Chart(document.getElementById("catChart"), {
                    type: "bar",
                    data: {
                        labels: ' . json_encode($catLabels) . ',
                        datasets: [{
                            label: "ተመዝጋቢዎች",
                            data: ' . json_encode($catCounts) . ',
                            backgroundColor: "#06b6d4"
                        }]
                    },
                    options: { responsive: true, plugins: { legend: { display: false } } }
                });
            </script>
        </body>
        </html>';

        return response($html);
    });

    // -------------------- PARTICIPANTS LIST (search + filter) --------------------
    Route::get('/participants', function (Request $request) use ($categories, $headStyles, $navbar) {
        $query = Participant::query()
            ->search($request->get('q'))
            ->category($request->get('category'))
            ->status($request->get('status'))
            ->latest();

        $participants = $query->paginate(15)->withQueryString();

        $catFilterOptions = '<option value="all">ሁሉም ዘርፎች</option>';
        foreach ($categories as $key => $label) {
            $sel = $request->get('category') === $key ? 'selected' : '';
            $catFilterOptions .= '<option value="' . $key . '" ' . $sel . '>' . $label . '</option>';
        }

        $statusOptions = '';
        foreach (['all' => 'ሁሉም ሁኔታዎች', 'pending' => 'በመጠባበቅ ላይ', 'approved' => 'የጸደቁ', 'rejected' => 'ውድቅ የተደረጉ'] as $key => $label) {
            $sel = $request->get('status', 'all') === $key ? 'selected' : '';
            $statusOptions .= '<option value="' . $key . '" ' . $sel . '>' . $label . '</option>';
        }

        $rows = '';
        foreach ($participants as $p) {
            $statusBadge = match ($p->status) {
                'approved' => '<span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-semibold">የጸደቀ</span>',
                'rejected' => '<span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-semibold">ውድቅ</span>',
                default    => '<span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-semibold">በመጠባበቅ</span>',
            };

            $rows .= '
            <tr class="hover:bg-gray-50 border-b">
                <td class="p-3">
                    <p class="font-semibold text-gray-800">' . htmlspecialchars($p->name) . '</p>
                    <p class="text-xs text-gray-500">' . htmlspecialchars($p->phone) . '</p>
                </td>
                <td class="p-3 text-sm text-gray-600">' . htmlspecialchars($categories[$p->category] ?? $p->category) . '</td>
                <td class="p-3 text-sm text-gray-600">' . htmlspecialchars($p->project_title ?? '-') . '</td>
                <td class="p-3">' . $statusBadge . '</td>
                <td class="p-3 text-center">
                    <a href="/storage/' . $p->screenshot . '" target="_blank" class="text-blue-600 hover:underline text-sm">ስክሪንሻት</a>
                </td>
                <td class="p-3 text-center whitespace-nowrap">
                    <form action="/admin/participants/' . $p->id . '/approve" method="POST" class="inline">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <button class="text-green-600 hover:underline text-sm mr-2">አጽድቅ</button>
                    </form>
                    <form action="/admin/participants/' . $p->id . '/reject" method="POST" class="inline">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <button class="text-red-500 hover:underline text-sm mr-2">ውድቅ</button>
                    </form>
                    <form action="/admin/participants/' . $p->id . '" method="POST" class="inline" onsubmit="return confirm(\'እርግጠኛ ኖት?\');">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button class="text-gray-400 hover:text-gray-700 text-sm">ሰርዝ</button>
                    </form>
                </td>
            </tr>';
        }

        if ($participants->isEmpty()) {
            $rows = '<tr><td colspan="6" class="text-center p-6 text-gray-500">ምንም ውጤት አልተገኘም።</td></tr>';
        }

        $html = '
        <!DOCTYPE html>
        <html lang="am">
        <head>' . $headStyles . '<title>ተሳታፊዎች</title></head>
        <body class="bg-gray-100 min-h-screen">
            ' . $navbar('participants') . '
            <div class="max-w-6xl mx-auto p-6">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-2xl font-bold text-gray-800">የተመዘገቡ ተሳታፊዎች</h1>
                    <a href="/admin/participants/export?' . http_build_query($request->query()) . '" class="bg-gray-800 text-white text-sm font-semibold px-4 py-2 rounded hover:bg-gray-700">CSV አውርድ</a>
                </div>

                <form method="GET" class="bg-white p-4 rounded-xl shadow mb-4 grid grid-cols-1 md:grid-cols-4 gap-3">
                    <input type="text" name="q" value="' . htmlspecialchars($request->get('q', '')) . '" placeholder="በስም/ስልክ ይፈልጉ..." class="border border-gray-300 p-2 rounded md:col-span-2">
                    <select name="category" class="border border-gray-300 p-2 rounded">' . $catFilterOptions . '</select>
                    <select name="status" class="border border-gray-300 p-2 rounded">' . $statusOptions . '</select>
                    <button type="submit" class="md:col-span-4 bg-cyan-600 text-white font-semibold py-2 rounded hover:bg-cyan-500">ፈልግ</button>
                </form>

                <div class="bg-white rounded-xl shadow overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-800 text-white text-sm">
                            <tr>
                                <th class="p-3">ስም</th>
                                <th class="p-3">ዘርፍ</th>
                                <th class="p-3">ፕሮጀክት</th>
                                <th class="p-3">ሁኔታ</th>
                                <th class="p-3 text-center">ማረጋገጫ</th>
                                <th class="p-3 text-center">ተግባር</th>
                            </tr>
                        </thead>
                        <tbody>' . $rows . '</tbody>
                    </table>
                </div>

                <div class="mt-4">' . $participants->links() . '</div>
            </div>
        </body>
        </html>';

        return response($html);
    });

    // -------------------- APPROVE / REJECT / DELETE --------------------
    Route::post('/participants/{id}/approve', function ($id) {
        Participant::findOrFail($id)->update(['status' => 'approved']);
        return redirect()->back();
    });

    Route::post('/participants/{id}/reject', function ($id) {
        Participant::findOrFail($id)->update(['status' => 'rejected']);
        return redirect()->back();
    });

    Route::delete('/participants/{id}', function ($id) {
        $participant = Participant::findOrFail($id);
        \Illuminate\Support\Facades\Storage::disk('public')->delete($participant->screenshot);
        $participant->delete();
        return redirect()->back();
    });

    // -------------------- CSV EXPORT --------------------
    Route::get('/participants/export', function (Request $request) use ($categories) {
        $participants = Participant::query()
            ->search($request->get('q'))
            ->category($request->get('category'))
            ->status($request->get('status'))
            ->latest()
            ->get();

        $filename = 'participants_' . now()->format('Y_m_d_His') . '.csv';

        return response()->streamDownload(function () use ($participants, $categories) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Name', 'Phone', 'Email', 'Grade', 'Category', 'Team', 'Project Title', 'Status', 'Registered At']);
            foreach ($participants as $p) {
                fputcsv($out, [
                    $p->id,
                    $p->name,
                    $p->phone,
                    $p->email,
                    $p->grade,
                    $categories[$p->category] ?? $p->category,
                    $p->team_name,
                    $p->project_title,
                    $p->status,
                    $p->created_at,
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    });
});
