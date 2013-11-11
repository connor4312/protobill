<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	/**
	 * Simple function to return a generic error if false was passed back from
	 * a repo. Backbone should take care of these just fine.
	 * 
	 * @param mixed $result
	 * @return Response
	 */
	protected function jsonResult($result) {

        if ($result === false) {
        	return Response::json(array(), 400);
        } else {
        	return Response::json((array) $result, 200);
        }

	}

}