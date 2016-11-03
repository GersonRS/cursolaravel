<?php

namespace Ecommerce\Presenters;

use Ecommerce\Transformers\CupomTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class CupomPresenter
 *
 * @package namespace Ecommerce\Presenters;
 */
class CupomPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new CupomTransformer();
    }
}
