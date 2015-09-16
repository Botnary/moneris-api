<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * E-mail: my.test@live.ca
 * Date: 9/16/2015
 * Time: 11:51 AM
 */

namespace Zone\PaymentGateway\Helper;


class Recur
{
    private $recur_unit;
    private $start_now;
    private $start_date;
    private $num_recurs;
    private $period;
    private $recur_amount;

    /**
     * @return mixed
     */
    public function getRecurUnit()
    {
        return $this->recur_unit;
    }

    /**
     * @param mixed $recur_unit
     */
    public function setRecurUnit($recur_unit)
    {
        $this->recur_unit = $recur_unit;
    }

    /**
     * @return bool
     */
    public function getStartNow()
    {
        return $this->start_now;
    }

    /**
     * @param bool $start_now
     */
    public function setStartNow($start_now)
    {
        $this->start_now = $start_now;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * @param \DateTime $start_date
     */
    public function setStartDate(\DateTime $start_date)
    {
        $this->start_date = $start_date;
    }

    /**
     * @return mixed
     */
    public function getNumRecurs()
    {
        return $this->num_recurs;
    }

    /**
     * @param mixed $num_recurs
     */
    public function setNumRecurs($num_recurs)
    {
        $this->num_recurs = $num_recurs;
    }

    /**
     * @return mixed
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param mixed $period
     */
    public function setPeriod($period)
    {
        $this->period = $period;
    }

    /**
     * @return mixed
     */
    public function getRecurAmount()
    {
        return $this->recur_amount;
    }

    /**
     * @param mixed $recur_amount
     */
    public function setRecurAmount($recur_amount)
    {
        $this->recur_amount = $recur_amount;
    }


}