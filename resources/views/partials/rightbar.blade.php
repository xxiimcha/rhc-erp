<!-- Rightbar Sidebar -->
<div class="offcanvas offcanvas-end" id="offcanvas-rightsidabar">
    <div class="card h-100 rounded-0" data-simplebar="init">
        <div class="card-header bg-light">
            <h6 class="card-title text-uppercase">Activities</h6>
            <div class="card-addon">
                <button class="btn btn-label-danger" data-bs-dismiss="offcanvas">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="">
                <h3 class="card-title">Company summary</h3>
                @foreach ([
                    ['label' => 'Server Load', 'value' => 489, 'percent' => 49.4, 'class' => 'success'],
                    ['label' => 'Members online', 'value' => 3450, 'percent' => 34.6, 'class' => 'danger'],
                    ['label' => "Today's revenue", 'value' => '$18,390', 'percent' => 20, 'class' => 'warning'],
                    ['label' => 'Expected profit', 'value' => '$23,461', 'percent' => 60, 'class' => 'info']
                ] as $item)
                    <div class="border rounded p-2 mb-3">
                        <p class="text-muted mb-2">{{ $item['label'] }}</p>
                        <h4 class="fs-16 mb-2">{{ $item['value'] }}</h4>
                        <div class="progress progress-sm" style="height:4px;">
                            <div class="progress-bar bg-{{ $item['class'] }}" style="width: {{ $item['percent'] }}%"></div>
                        </div>
                        <p class="text-muted mb-0 mt-1">{{ $item['percent'] }}% <span>Avg</span></p>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                <h3 class="card-title">Latest log</h3>
                <div class="timeline">
                    @foreach ([
                        ['text' => '12 new users registered', 'time' => 'Just now', 'class' => 'primary'],
                        ['text' => 'System shutdown', 'time' => '2 mins', 'class' => 'success', 'badge' => 'pending'],
                        ['text' => 'New invoice received', 'time' => '3 mins', 'class' => 'primary'],
                        ['text' => 'New order received', 'time' => '10 mins', 'class' => 'danger', 'badge' => 'urgent'],
                        ['text' => 'Production server down', 'time' => '1 hrs', 'class' => 'warning'],
                        ['text' => 'System error <a href="#">check</a>', 'time' => '2 hrs', 'class' => 'info'],
                        ['text' => 'DB overloaded 80%', 'time' => '5 hrs', 'class' => 'secondary'],
                        ['text' => 'Production server up', 'time' => '6 hrs', 'class' => 'success']
                    ] as $log)
                        <div class="timeline-item">
                            <div class="timeline-pin"><i class="marker marker-dot text-{{ $log['class'] }}"></i></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between">
                                    <p class="mb-0">
                                        {!! $log['text'] !!}
                                        @isset($log['badge']) <span class="badge badge-label-{{ $log['class'] }}">{{ $log['badge'] }}</span> @endisset
                                    </p>
                                    <span class="text-muted">{{ $log['time'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4">
                <h3 class="card-title">Upcoming activities</h3>
                <div class="timeline timeline-timed">
                    @foreach ([
                        ['time' => '10:00', 'icon' => 'primary', 'desc' => 'Meeting with', 'avatars' => ['1', '2', '3']],
                        ['time' => '12:45', 'icon' => 'warning', 'desc' => 'Lorem ipsum dolor sit amit, consectetur eiusmdd tempor incididunt ut labore et dolore magna elit.'],
                        ['time' => '14:00', 'icon' => 'danger', 'desc' => 'Received a new feedback on <a href="#">GoFinance</a> App product.'],
                        ['time' => '15:20', 'icon' => 'success', 'desc' => 'Lorem ipsum dolor sit amit, consectetur eiusmdd tempor incididunt ut labore.'],
                        ['time' => '17:00', 'icon' => 'info', 'desc' => 'Make Deposit <a href="#">USD 700</a> to ESL.'],
                    ] as $event)
                        <div class="timeline-item">
                            <span class="timeline-time">{{ $event['time'] }}</span>
                            <div class="timeline-pin"><i class="marker marker-circle text-{{ $event['icon'] }}"></i></div>
                            <div class="timeline-content">
                                <p class="mb-0">
                                    {!! $event['desc'] !!}
                                    @isset($event['avatars'])
                                        <div class="avatar-group ms-2">
                                            @foreach ($event['avatars'] as $avatar)
                                                <div class="avatar avatar-circle">
                                                    <img src="{{ asset('assets/images/users/avatar-' . $avatar . '.png') }}" class="avatar-2xs" />
                                                </div>
                                            @endforeach
                                        </div>
                                    @endisset
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
