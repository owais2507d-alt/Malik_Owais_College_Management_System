<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@fiesta.edu'],
            [
                'name' => 'Fiesta Admin',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'phone' => '+92 300 0000000',
            ]
        );

        $students = User::factory(15)->student()->create();

        $categories = collect([
            ['name' => 'Sports', 'description' => 'Inter-collegiate tournaments, matches and athletic competitions.'],
            ['name' => 'Seminars', 'description' => 'Guest lectures, panels and academic talks by industry experts.'],
            ['name' => 'Workshops', 'description' => 'Hands-on skill-building sessions across technology and arts.'],
            ['name' => 'Cultural Events', 'description' => 'Music, dance, drama and celebrations of our diverse cultures.'],
            ['name' => 'Technical', 'description' => 'Hackathons, robotics, coding contests and tech showcases.'],
            ['name' => 'Literary & Arts', 'description' => 'Debates, poetry, photography, and creative writing.'],
        ])->map(fn (array $category) => EventCategory::updateOrCreate(
            ['name' => $category['name']],
            ['description' => $category['description']]
        ));

        $events = $this->seedEvents($categories);

        $this->seedRegistrations($students, $events);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, EventCategory>  $categories
     * @return \Illuminate\Support\Collection<int, Event>
     */
    private function seedEvents($categories): \Illuminate\Support\Collection
    {
        $definitions = [
            [
                'title' => 'Voices Unmuted: Opening Ceremony',
                'category' => 'Cultural Events',
                'days' => 12,
                'start' => '10:00:00',
                'end' => '13:00:00',
                'venue' => 'Main Auditorium',
                'deadline_days' => 9,
                'capacity' => 300,
                'status' => Event::STATUS_UPCOMING,
                'description' => "The grand opening of International Fiesta 2026 — \"Voices Unmuted: The Spectrum\". Expect a parade of cultures, a keynote from our chief guest, and the official lighting of the spectrum lantern.",
            ],
            [
                'title' => 'Spectrum Hackathon',
                'category' => 'Technical',
                'days' => 18,
                'start' => '09:00:00',
                'end' => '21:00:00',
                'venue' => 'Innovation Lab',
                'deadline_days' => 14,
                'capacity' => 60,
                'status' => Event::STATUS_UPCOMING,
                'description' => 'A 12-hour build marathon where teams turn bold ideas into working prototypes. Mentors from industry will guide you from concept to demo.',
            ],
            [
                'title' => 'Inter-College Football Cup',
                'category' => 'Sports',
                'days' => 22,
                'start' => '15:00:00',
                'end' => '18:00:00',
                'venue' => 'Sports Complex',
                'deadline_days' => 18,
                'capacity' => 120,
                'status' => Event::STATUS_UPCOMING,
                'description' => 'Eight teams, one trophy. Cheer for your college in the flagship football tournament of Fiesta 2026, complete with live commentary and half-time performances.',
            ],
            [
                'title' => 'Public Speaking Masterclass',
                'category' => 'Seminars',
                'days' => 8,
                'start' => '11:00:00',
                'end' => '13:00:00',
                'venue' => 'Seminar Hall A',
                'deadline_days' => -1,
                'capacity' => 80,
                'status' => Event::STATUS_UPCOMING,
                'description' => 'Learn how to command a room. This interactive masterclass covers stage presence, argument structure, and handling Q&A with confidence.',
            ],
            [
                'title' => 'AI & Web Development Workshop',
                'category' => 'Workshops',
                'days' => 5,
                'start' => '10:00:00',
                'end' => '16:00:00',
                'venue' => 'Innovation Lab',
                'deadline_days' => 3,
                'capacity' => 10,
                'status' => Event::STATUS_UPCOMING,
                'description' => 'A full-day hands-on workshop blending modern web frameworks with practical AI tooling. Bring your laptop — you will ship a live project by the end.',
            ],
            [
                'title' => 'Spectrum Night: Cultural Showcase',
                'category' => 'Cultural Events',
                'days' => 25,
                'start' => '18:00:00',
                'end' => '22:00:00',
                'venue' => 'Open Air Theatre',
                'deadline_days' => 22,
                'capacity' => 400,
                'status' => Event::STATUS_UPCOMING,
                'description' => 'The marquee evening of Fiesta 2026. Music, dance and drama troupes from every department perform under the stars in a celebration of the spectrum of talent.',
            ],
            [
                'title' => 'Debate: Voices of the Future',
                'category' => 'Literary & Arts',
                'days' => 0,
                'start' => '10:00:00',
                'end' => '14:00:00',
                'venue' => 'Conference Room B',
                'deadline_days' => -1,
                'capacity' => 50,
                'status' => Event::STATUS_ONGOING,
                'description' => 'A moderated parliamentary debate on the role of student voices in shaping public policy. Rounds run all morning with an open audience Q&A.',
            ],
            [
                'title' => 'Photography Walk: Campus in Focus',
                'category' => 'Literary & Arts',
                'days' => 0,
                'start' => '15:00:00',
                'end' => '18:00:00',
                'venue' => 'Main Gate',
                'deadline_days' => -1,
                'capacity' => 30,
                'status' => Event::STATUS_ONGOING,
                'description' => 'A guided photo walk across campus exploring light, colour and composition. All skill levels welcome — phones and DSLRs alike.',
            ],
            [
                'title' => 'Robotics Challenge',
                'category' => 'Technical',
                'days' => -8,
                'start' => '09:00:00',
                'end' => '17:00:00',
                'venue' => 'Innovation Lab',
                'deadline_days' => -12,
                'capacity' => 45,
                'status' => Event::STATUS_COMPLETED,
                'description' => 'Autonomous bots raced, battled and solved mazes in this day-long challenge. Congratulations to Team Circuit Breakers for taking the crown.',
            ],
            [
                'title' => 'Health & Wellness Seminar',
                'category' => 'Seminars',
                'days' => -15,
                'start' => '11:00:00',
                'end' => '13:00:00',
                'venue' => 'Seminar Hall A',
                'deadline_days' => -18,
                'capacity' => 100,
                'status' => Event::STATUS_COMPLETED,
                'description' => 'A physician-led session on nutrition, sleep and stress management for students, with free BMI screenings after the talk.',
            ],
            [
                'title' => 'Table Tennis Showdown',
                'category' => 'Sports',
                'days' => -20,
                'start' => '14:00:00',
                'end' => '18:00:00',
                'venue' => 'Sports Complex',
                'deadline_days' => -24,
                'capacity' => 64,
                'status' => Event::STATUS_COMPLETED,
                'description' => 'Sixty-four players entered, one left standing. The knockout draw delivered upsets, smashes and a five-set final for the ages.',
            ],
            [
                'title' => 'Poetry Open Mic: Unmuted',
                'category' => 'Literary & Arts',
                'days' => 30,
                'start' => '17:00:00',
                'end' => '19:30:00',
                'venue' => 'Open Air Theatre',
                'deadline_days' => 27,
                'capacity' => 70,
                'status' => Event::STATUS_UPCOMING,
                'description' => 'Every voice deserves a microphone. Bring your poems, spoken word or personal essays to our open mic night — three minutes per artist.',
            ],
        ];

        return collect($definitions)->map(function (array $def) use ($categories) {
            $category = $categories->firstWhere('name', $def['category']);
            $date = now()->addDays($def['days'])->format('Y-m-d');

            return Event::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($def['title'])],
                [
                    'category_id' => $category?->id ?? $categories->first()?->id,
                    'title' => $def['title'],
                    'description' => $def['description'],
                    'event_date' => $date,
                    'start_time' => $def['start'],
                    'end_time' => $def['end'],
                    'venue' => $def['venue'],
                    'image' => null,
                    'max_participants' => $def['capacity'],
                    'registration_deadline' => now()->addDays($def['deadline_days'])->format('Y-m-d H:i:s'),
                    'status' => $def['status'],
                ]
            );
        });
    }

    /**
     * @param  \Illuminate\Support\Collection<int, User>  $students
     * @param  \Illuminate\Support\Collection<int, Event>  $events
     */
    private function seedRegistrations($students, $events): void
    {
        $statuses = [
            EventRegistration::STATUS_PENDING,
            EventRegistration::STATUS_APPROVED,
            EventRegistration::STATUS_APPROVED,
            EventRegistration::STATUS_APPROVED,
            EventRegistration::STATUS_REJECTED,
        ];

        foreach ($events as $event) {
            $count = min($students->count(), (int) max(3, $event->max_participants / 4));
            $participants = $students->shuffle()->take($count);

            foreach ($participants as $index => $student) {
                $status = $event->status === Event::STATUS_COMPLETED
                    ? EventRegistration::STATUS_APPROVED
                    : $statuses[($index + $event->id) % count($statuses)];

                EventRegistration::updateOrCreate(
                    ['user_id' => $student->id, 'event_id' => $event->id],
                    ['status' => $status, 'registered_at' => now()->subDays(rand(1, 10))]
                );
            }
        }

        // Fill the workshop to capacity so the "fully booked" state is demoable.
        $workshop = $events->firstWhere('slug', 'ai-web-development-workshop');

        if ($workshop) {
            $used = EventRegistration::where('event_id', $workshop->id)
                ->whereIn('status', [EventRegistration::STATUS_PENDING, EventRegistration::STATUS_APPROVED])
                ->pluck('user_id');

            $available = $students
                ->reject(fn (User $student) => $used->contains($student->id))
                ->shuffle();

            foreach ($available as $student) {
                if ($workshop->seatsTaken() >= $workshop->max_participants) {
                    break;
                }

                EventRegistration::create([
                    'user_id' => $student->id,
                    'event_id' => $workshop->id,
                    'status' => EventRegistration::STATUS_APPROVED,
                    'registered_at' => now()->subDays(rand(1, 5)),
                ]);
            }
        }
    }
}
