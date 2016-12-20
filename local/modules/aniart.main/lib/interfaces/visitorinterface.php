<?php

namespace Aniart\Main\Interfaces;


interface VisitorInterface
{
    public function visit(VisitableInterface $instance);
}