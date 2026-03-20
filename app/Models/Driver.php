use App\Models\Job;

public function jobs()
{
    return $this->hasMany(Job::class);
}
