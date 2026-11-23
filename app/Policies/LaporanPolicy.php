<?php

namespace App\Policies;

use App\Models\Laporan;
use App\Models\User;

class LaporanPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Laporan $laporan): bool
    {
        return $user->isAdmin() || $laporan->pelapor_id === $user->id;
    }

    public function create(User $user): bool
    {
        return ! $user->isAdmin();
    }

    public function update(User $user, Laporan $laporan): bool
    {
        return ! $user->isAdmin()
            && $laporan->pelapor_id === $user->id
            && $laporan->isEditable();
    }

    public function delete(User $user, Laporan $laporan): bool
    {
        return $this->update($user, $laporan);
    }

    public function changeStatus(User $user, Laporan $laporan): bool
    {
        return $user->isAdmin();
    }
}
