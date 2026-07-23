<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Match old text-based flat_info values to real flats for existing pending residents.
        $flats = DB::table('flats')
            ->join('buildings', 'buildings.id', '=', 'flats.building_id')
            ->select('flats.id', 'flats.flat_number', 'buildings.name as building_name')
            ->get();

        DB::table('users')
            ->where('role', 'resident')
            ->whereNull('requested_flat_id')
            ->whereNotNull('flat_info')
            ->whereIn('status', ['pending_verification', 'pending_approval'])
            ->orderBy('id')
            ->get()
            ->each(function (object $resident) use ($flats): void {
                $flatInfo = strtolower((string) $resident->flat_info);

                // Best-effort matching keeps old seed/user data compatible with requested_flat_id.
                $flat = $flats->first(function (object $flat) use ($flatInfo): bool {
                    $flatNumberMatches = str_contains($flatInfo, strtolower((string) $flat->flat_number));
                    $buildingMatches = str_contains($flatInfo, strtolower((string) $flat->building_name));

                    return $flatNumberMatches && ($buildingMatches || $flatInfo !== '');
                });

                if ($flat) {
                    DB::table('users')
                        ->where('id', $resident->id)
                        ->update(['requested_flat_id' => $flat->id]);
                }
            });
    }

    public function down(): void
    {
        // Rollback only clears pending requested flats; approved ResidentProfiles are left intact.
        DB::table('users')
            ->where('role', 'resident')
            ->whereIn('status', ['pending_verification', 'pending_approval'])
            ->update(['requested_flat_id' => null]);
    }
};
