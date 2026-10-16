<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadRequest;
use App\Jobs\SendLeadNotification;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function store(StoreLeadRequest $request)
    {
        $data = $request->validated();
        unset($data['website']);

        $data['duplicate_hash'] = hash('sha256', $data['email'].'|'.mb_strtolower($data['message']));
        $data['ip_hash'] = hash_hmac('sha256', (string) $request->ip(), config('app.key'));
        $data['user_agent'] = mb_substr((string) $request->userAgent(), 0, 500);

        $duplicate = Lead::where('duplicate_hash', $data['duplicate_hash'])
            ->where('created_at', '>=', now()->subMinutes(15))->exists();

        if ($duplicate) {
            return back()->with('success', 'We already received this inquiry and will respond shortly.');
        }

        $lead = DB::transaction(fn () => Lead::create($data));
        SendLeadNotification::dispatch($lead)->afterCommit();

        return back()->with('success', 'Thank you. Your project inquiry has been received.');
    }
}
