<?php

namespace Aniart\Main\Interfaces;


interface VisitableInterface
{
    public function accept(VisitorInterface $instance);
}