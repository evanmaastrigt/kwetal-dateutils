<?php

namespace Kwetal\DateUtils\DateTime;

class DateTime extends \DateTime
{
    /**
     * @var array $labels
     */
    protected $labels = [];

    /**
     * Returns the labels for this day
     *
     * @return array
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * Sets the Labels for the day
     *
     * @param $labels
     * @return $this
     */
    public function setLabels($labels)
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * Add a label for this day
     * 
     * @param $label
     * @return $this
     */
    public function addLabel($label)
    {
        $this->labels[] = $label;

        return $this;
    }
} 
