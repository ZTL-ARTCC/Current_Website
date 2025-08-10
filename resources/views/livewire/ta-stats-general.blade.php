<div>
    <div class="row bg-warning w-100 my-2 rounded" wire:loading>
        <h3 class="text-center p-2"><i class="fa-solid fa-hourglass-start me-2"></i> Refreshing Dataset... please wait</h3>
    </div>
    <div class="row mt-4">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    Student No-Shows
                </div>
                <div class="card-body">
                    @forelse($no_shows as $no_show)
                    <div><i class="fa-solid fa-skull-crossbones mx-2"></i>{{ $no_show }}</div>
                    @empty
                    <div><i class="fa-solid fa-thumbs-up mx-2"></i>No multiple student no-shows within the last 45-days have been logged.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    Average Tickets/Certification
                </div>
                <div class="card-body">
                    <canvas id="tickets_by_cert" width="500" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    Completion Ratios
                    <select class="form-select form-select-sm float-end" wire:model.change='position_select'>
                        <option value="S1" selected>S1</option>
                        <option value="S2">S2</option>
                        <option value="S3">S3</option>
                        <option value="C1">C1</option>
                        <option value="CLT_ATCT">CLT ATCT</option>
                        <option value="CLT_APP">CLT APP</option>
                        <option value="ATL_ATCT">ATL ATCT</option>
                        <option value="A80">A80</option>
                    </select>
                </div>
                <div class="card-body">
                    <canvas id="completion_ratio" width="500" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    Annual Lookback
                </div>
                <div class="card-body">
                    <canvas id="annual_lookback" width="500" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    30/60/90-Day Lookback
                </div>
                <div class="card-body">
                    <canvas id="monthly_lookback" width="500" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@script
<script>
    let annualLookbackData = {!!json_encode($this->lookback_annual) !!}
    const annualLookback = new Chart(
        document.getElementById('annual_lookback'), {
            type: 'line',
            data: {
                labels: annualLookbackData.labels,
                datasets: [{
                        label: 'Last Year',
                        yAxisID: 'y',
                        data: annualLookbackData.last_year
                    },
                    {
                        label: 'This Year',
                        yAxisID: 'y',
                        data: annualLookbackData.this_year
                    }
                ],
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Annual Lookback (12-Month)'
                    }
                }
            }
        },
    );
    
    let monthlyLookbackData = {!!json_encode($this->lookback_monthly) !!}
    const monthlyLookback = new Chart(
        document.getElementById('monthly_lookback'), {
            type: 'bar',
            data: {
                labels: ['30', '60', '90'],
                datasets: [{
                        label: 'Current',
                        data: monthlyLookbackData.now
                    },
                    {
                        label: 'Previous',
                        data: monthlyLookbackData.prev
                    }
                ],
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: '3-Month Lookback (30/60/90)'
                    }
                }
            }
        }
    );

    const completionRatio = new Chart(
        document.getElementById('completion_ratio'), {
            type: 'bar',
            data: {
                labels: {!!json_encode($this->session_ids) !!},
                datasets: [{
                    data: {!!json_encode($this->completion_ratios) !!}
                }]
            },
            options: {
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Completion Ratio of Lessons'
                    },
                },
                scales: {
                    y: {
                        ticks: {
                            display: true,
                            autoSkip: false
                        }
                    }
                }
            }
        }
    )


    $wire.on('updateCompletionChart', (event) => {
        completionRatio.data.labels = event.labels;
        completionRatio.data.datasets[0].data = event.data;
        completionRatio.update();
    });

    const ticketsByCert = new Chart(
        document.getElementById('tickets_by_cert'), {
            type: 'bar',
            data: {
                labels: {!!json_encode($this->cert_types) !!},
                datasets: [{
                    data: {!!json_encode($this->tickets_by_certs) !!}
                }]
            },
            options: {
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Average Number of Tickets by Student'
                    }
                }
            }
        }
    )
    
</script>
@endscript