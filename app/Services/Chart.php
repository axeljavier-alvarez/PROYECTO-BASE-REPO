<?php

namespace App\Services;

class Chart
{
    protected array $options = [];

    public function __construct(string $type = 'line')
    {
        $this->options = [
            'chart' => [
                'type' => $type,
                'height' => 350,
                'toolbar' => ['show' => true],
                'fontFamily' => 'inherit',
                'animations' => ['enabled' => true, 'easing' => 'easeinout', 'speed' => 800],
            ],
            'series' => [],
            'colors' => ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
            'dataLabels' => ['enabled' => false],
            'stroke' => ['width' => 2, 'curve' => 'smooth'],
            'grid' => ['borderColor' => '#e2e8f0', 'strokeDashArray' => 4],
        ];
    }

    public static function make(string $type = 'line'): self {
        return new self($type);
    }

    public function series(array $series): self {
        $this->options['series'] = $series;
        return $this;
    }

    public function labels(array $labels): self {
        $this->options['labels'] = $labels;
        if (!in_array($this->options['chart']['type'], ['donut', 'pie'])) {
            data_set($this->options, 'xaxis.categories', $labels);
        }
        return $this;
    }

    public function set(string $key, mixed $value): self {
        data_set($this->options, $key, $value);
        return $this;
    }

    public function formatter(string $jsExpression): self {
        $this->options['custom_formatter'] = $jsExpression;
        return $this;
    }

    public function addGoal(int $value, string $label, string $color = '#ef4444'): self {
        $this->options['annotations']['yaxis'][] = [
            'y' => $value, 'borderColor' => $color,
            'label' => ['text' => $label, 'style' => ['color' => '#fff', 'background' => $color]]
        ];
        return $this;
    }

    public function group(string $name): self {
        $this->options['chart']['group'] = $name;
        return $this;
    }

    public function build(): array {
        return $this->options;
    }
}