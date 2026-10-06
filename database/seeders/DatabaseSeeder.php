namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@gmail.com'], [
            'name' => 'System Admin',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::updateOrCreate(['email' => 'storekeeper@test.com'], [
            'name' => 'Storekeeper User',
            'password' => Hash::make('password123'),
            'role' => 'storekeeper',
        ]);

        User::updateOrCreate(['email' => 'manager@test.com'], [
            'name' => 'Site Manager',
            'password' => Hash::make('password123'),
            'role' => 'manager',
        ]);
    }
}