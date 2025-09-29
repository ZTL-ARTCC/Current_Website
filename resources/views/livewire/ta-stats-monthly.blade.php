<div>
    <div class="row bg-warning w-100 my-2 rounded" wire:loading>
        <h3 class="text-center p-2"><i class="fa-solid fa-hourglass-start me-2"></i> Refreshing Dataset... please wait</h3>
    </div>
    <div class="row">
        <div class="col-sm-2 col-xs-12">
            <div class="card">
                {{ html()->select('date_select', $year_of_months_lookback, implode(' ', $stats['dateSelect']))->class(['form-select'])->attributes(['wire:model.change'=>'date_select']) }}
            </div>
            <div class="card text-center mt-2">
                <div class="card-header">Sessions Per Month</div>
                <div class="card-body">
                    <h2>{{ $stats['sessionsPerMonth'] }}</h2>
                </div>
            </div>
            <div class="card text-center mt-2">
                <div class="card-header">Staff Ins/Mtr</div>
                <div class="card-body">
                    <h2>{{ $stats['totalInstructors'] }} / {{ $stats['totalMentors'] }}</h2>
                </div>
            </div>
            <div class="card text-center mt-2">
                <div class="card-header">Unique Students</div>
                <div class="card-body">
                    <h2>{{ $stats['uniqueStudents'] }}</h2>
                </div>
            </div>
            <div class="card text-center mt-2">
                <div class="card-header">OTS Pass/Fail</div>
                <div class="card-body">
                    <h2><span class="text-success">{{ $stats['otsPerMonth']['pass'] }}</span> / <span class="text-danger">{{ $stats['otsPerMonth']['fail'] }}</span></h2>
                </div>
            </div>
            <div class="card text-center mt-2">
                <div class="card-header">Student<br />No-Shows</div>
                <div class="card-body">
                    <h2>{{ $stats['sessionsPerMonthNoShow'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-sm-5 col-xs-12">
            <div class="card">
                <canvas id="sessions_per_month" width="500" height="400"></canvas>
            </div>
            <br />
            <div class="card">
                <canvas id="sessions_by_staff_member" width="500" height="400"></canvas>
            </div>
        </div>
        <div class="col-sm-5 col-xs-12">
            <div class="card">
                <canvas id="average_session_duration" width="500" height="400"></canvas>
            </div>
            <br />
            <div class="card">
                <canvas id="students_requiring_training" width="500" height="400"></canvas>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mt-2">
                <div class="card-header">TA's Monthly Report
                    <div class="float-end"><i class="fas fa-clipboard" onclick="copyToClipboard('taReport');" title="Copy to clipboard"></i></div>
                </div>
                <div class="card-body">
                    <textarea id="taReport" rows="3" class="form-control" readonly>{{ $stats['taMonthlyReport'] }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
@script
<script>
    let chartData = {!!json_encode($this->graph_data)!!}
    let inclusiveDates = '(' + {!!json_encode($this->inclusive_dates) !!} + ')'
    let trainerMinSessions = {{$this->trainer_min_sessions}}
    let todaysDate = {!!json_encode($this->todays_date) !!}

    const sessionsPerMonth = new Chart(
        document.getElementById('sessions_per_month'), {
            type: 'bar',
            data: {
                labels: chartData.sessions_per_month.labels,
                datasets: [{
                    data: chartData.sessions_per_month.data
                }]
            },
            options: {
                indexAxis: 'x',
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Sessions Per Month'
                    },
                    subtitle: {
                        display: true,
                        text: inclusiveDates
                    }
                },
                scales: {
                    y: {
                        title: {
                            display: true,
                            align: 'center',
                            text: 'Number of Sessions'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            align: 'center',
                            text: 'Session Type'
                        }
                    }
                }
            }
        }
    )
    const averageSessionDuration = new Chart(
        document.getElementById('average_session_duration'), {
            type: 'bar',
            data: {
                labels: chartData.session_duration.labels,
                datasets: [{
                   data: chartData.session_duration.data
                }]
            },
            options: {
                indexAxis: 'x',
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Average Session Duration'
                    },
                    subtitle: {
                        display: true,
                        text: 'Last Six Months'
                    }
                },
                scales: {
                    y: {
                        title: {
                            display: true,
                            align: 'center',
                            text: 'Average Session Duration (minutes)'
                        }
                    }
                }
            }
        }
    )
    const ctx1 = document.getElementById('sessions_by_staff_member').getContext('2d');
    const horizontalLinePlugin = {
        id: 'horizontalLine',
        afterDraw: (chart) => {
            const yValue = chart.scales.y.getPixelForValue(trainerMinSessions);
            const ctx = chart.ctx;
            ctx.save();
            ctx.beginPath();
            ctx.moveTo(chart.chartArea.left, yValue);
            ctx.lineTo(chart.chartArea.right, yValue);
            ctx.strokeStyle = 'red';
            ctx.lineWidth = 2;
            ctx.stroke();
            ctx.restore();
        }
    };
    const sessionsByStaffMember = new Chart(
        document.getElementById('sessions_by_staff_member'), {
            type: 'bar',
            data: {
                labels: chartData.sessions_by_staff.labels,
                datasets: chartData.sessions_by_staff.data
            },
            options: {
                indexAxis: 'x',
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        align: 'center'
                    },
                    title: {
                        display: true,
                        text: 'Sessions Per Month By Instructor/Mentor'
                    },
                    subtitle: {
                        display: true,
                        text: inclusiveDates
                    }
                },
                responsive: true,
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true,
                        title: {
                            display: true,
                            align: 'center',
                            text: 'Number of Sessions'
                        }
                    }
                }
            },
            plugins: [horizontalLinePlugin]
        }
    )
    const studentsRequiringTraining = new Chart(
        document.getElementById('students_requiring_training'), {
            type: 'bar',
            data: {
                labels: chartData.students_requiring_training.labels,
                datasets: [{
                   data: chartData.students_requiring_training.data
                }]
            },
            options: {
                indexAxis: 'x',
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Students Requiring Training'
                    },
                    subtitle: {
                        display: true,
                        text: 'As of ' + todaysDate
                    }
                },
                scales: {
                    y: {
                        title: {
                            display: true,
                            align: 'center',
                            text: 'Number of Students'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            align: 'center',
                            text: 'Student Type'
                        }
                    }

                }
            }
        }
    )
    $wire.on('changeSelectedMonth', (event) => {
        sessionsPerMonth.options.plugins.subtitle.text = '(' + event.inclusive_dates + ')';
        sessionsPerMonth.data.labels = event.graph_data.sessions_per_month.labels;
        sessionsPerMonth.data.datasets[0].data = event.graph_data.sessions_per_month.data;
        sessionsPerMonth.update();
        sessionsByStaffMember.options.plugins.subtitle.text = '(' + event.inclusive_dates + ')';
        sessionsByStaffMember.data.labels = event.graph_data.sessions_by_staff.labels;
        sessionsByStaffMember.data.datasets = event.graph_data.sessions_by_staff.data;
        sessionsByStaffMember.update();
    });
</script>
@endscript
