<?php
namespace Akaunting\Firewall\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Tarpit extends Model
{
    protected $table = 'firewall_tarpits';

    public static function addTry(string $ip_address)
    {
        $tarpit = Tarpit::where('ip_address', $ip_address)->first();
        if (!$tarpit) {
            $tarpit = new Tarpit();
            $tarpit->ip_address = $ip_address;
        }

        $tarpit->tries += 1;
        $tarpit->block_until = self::getBlockedUntil($tarpit);
        $tarpit->save();
    }

    public static function isBlocked(string $ip_address)
    {
        $tarpit = Tarpit::where('ip_address', $ip_address)->first();
        if (!$tarpit) {
            return false;
        }

        if (Carbon::parse($tarpit->block_until) < Carbon::now()) {
            return false;
        }
        return true;
    }

    public static function blockedUntil(string $ip_address)
    {
        $tarpit = Tarpit::where('ip_address', $ip_address)->first();
        if (!$tarpit) {
            return false;
        }

        if (Carbon::parse($tarpit->block_until) < Carbon::now()) {
            return false;
        }

        return Carbon::parse($tarpit->block_until);
    }

    public static function remove(string $ip_address)
    {
        $tarpit = Tarpit::where('ip_address', $ip_address)->first();
        if ($tarpit) {
            $tarpit->delete();
        }
    }

    private static function getBlockedUntil(Tarpit $tarpit)
    {
        $graceTries = config('firewall.middleware.tarpit.grace_tries');
        $penaltySeconds = config('firewall.middleware.tarpit.penalty_seconds');

        $penaltyTries =  max($tarpit->tries - $graceTries, 0);
        $penaltySquared = $penaltyTries * $penaltyTries;

        $penaltyInSeconds = $penaltySquared * $penaltySeconds;

        return Carbon::now()->addSeconds($penaltyInSeconds);
    }
}
