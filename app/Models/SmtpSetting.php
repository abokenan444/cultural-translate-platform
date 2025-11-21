<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmtpSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'from_address',
        'from_name',
        'is_active',
        'is_default',
        'last_tested_at',
        'test_passed',
        'test_error',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'test_passed' => 'boolean',
        'last_tested_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function setAsDefault()
    {
        // Remove default from all other settings
        static::where('id', '!=', $this->id)->update(['is_default' => false]);
        
        $this->update(['is_default' => true]);
    }

    public function testConnection()
    {
        try {
            $transport = new \Swift_SmtpTransport($this->host, $this->port, $this->encryption);
            $transport->setUsername($this->username);
            $transport->setPassword($this->password);

            $mailer = new \Swift_Mailer($transport);
            $mailer->getTransport()->start();

            $this->update([
                'test_passed' => true,
                'test_error' => null,
                'last_tested_at' => now(),
            ]);

            return true;
        } catch (\Exception $e) {
            $this->update([
                'test_passed' => false,
                'test_error' => $e->getMessage(),
                'last_tested_at' => now(),
            ]);

            return false;
        }
    }
}
