<?php

namespace App\Http\Controllers\Concerns;

use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Filtrado por «mis postgrados» del rol coordinador_postgrado.
 *
 * Para los demás roles (administrador, coordinador_general) los helpers
 * devuelven «sin filtro»: ven todos los postgrados, como hasta ahora.
 */
trait FiltrablePorCoordinador
{
    /**
     * True si el usuario es coordinador de postgrado (solo ve SUS postgrados).
     */
    private function esCoordinadorDePostgrado(User $user): bool
    {
        return $user->rol === 'coordinador_postgrado';
    }

    /**
     * IDs de postgrados que el usuario puede gestionar; null = sin filtro
     * (administrador y coordinador general ven todos, sin cambios).
     */
    private function idsPostgradosPermitidos(User $user): ?Collection
    {
        if (! $this->esCoordinadorDePostgrado($user)) {
            return null;
        }

        return $user->coordinadorPostgrado->postgrados->pluck('id_postgrado');
    }

    /**
     * True si el usuario puede gestionar el postgrado dado. Los coordinadores
     * de postgrado solo pueden con los suyos (y nunca con "null", usado para
     * bloquear la creación/borrado de postgrados: eso es del general/admin).
     */
    private function puedeGestionarPostgrado(User $user, ?string $idPostgrado): bool
    {
        $permitidos = $this->idsPostgradosPermitidos($user);

        if ($permitidos === null) {
            return true;
        }

        return $idPostgrado !== null && $permitidos->contains($idPostgrado);
    }
}
