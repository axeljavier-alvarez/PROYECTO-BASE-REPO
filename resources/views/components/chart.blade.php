@props(['config', 'event' => null])

@php $id = 'chart_' . md5(serialize($config) . microtime()); @endphp

<div 
    x-data="{
        chart: null,
        config: @js($config),
        
        init() {
            let options = JSON.parse(JSON.stringify(this.config));
            this.setupFunctions(options);
            
            this.chart = new ApexCharts(this.$refs.canvas, options);
            this.chart.render();

            // Theme Switcher Listener
            window.addEventListener('theme-changed', () => {
                this.chart.updateOptions({ theme: { mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light' } });
            });

            // Reactividad Livewire 4
            this.$watch('config', (val) => {
                if(val && this.chart) this.chart.updateSeries(val.series);
            });
        },

        setupFunctions(options) {
            if (options.custom_formatter) {
                const fn = new Function('val', 'return (' + options.custom_formatter + ')(val)');
                this.dotSet(options, 'tooltip.y.formatter', fn);
                this.dotSet(options, 'dataLabels.formatter', fn);
                this.dotSet(options, 'yaxis.labels.formatter', fn);
            }
            if (@js($event)) {
                this.dotSet(options, 'chart.events.dataPointSelection', (e, c, config) => {
                    $wire.dispatch(@js($event), { 
                        value: config.w.config.series[config.seriesIndex]?.data?.[config.dataPointIndex] || config.w.config.series[config.dataPointIndex],
                        label: config.w.config.labels[config.dataPointIndex]
                    });
                });
            }
        },

        dotSet(obj, path, val) {
            path.split('.').reduce((acc, key, i, arr) => 
                acc[key] = (i === arr.length - 1) ? val : (acc[key] || {}), obj);
        }
    }"
    wire:ignore
    {{ $attributes->class(['relative w-full bg-white dark:bg-slate-900 rounded-xl p-4 shadow-sm border border-slate-200 dark:border-slate-800']) }}
>
    <div wire:loading.flex class="absolute inset-0 z-10 items-center justify-center bg-white/50 dark:bg-slate-900/50 backdrop-blur-xs rounded-xl">
        <div class="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
    </div>

    <div x-ref="canvas"></div>

</div>