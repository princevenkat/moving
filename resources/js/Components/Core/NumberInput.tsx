import * as React from 'react';
import {Minus, Plus} from "lucide-react";





export interface InputProps
  extends React.InputHTMLAttributes<HTMLInputElement> {
  symbol?: string;
}

const NumberInput = React.forwardRef<HTMLInputElement, InputProps>(
  ({ symbol, className, ...props }, ref) => {
    const [hitMax, setHitMax] = React.useState(false);
    const [hitMin, setHitMin] = React.useState(false);
    const incrementInput = React.useRef<HTMLInputElement>(null);

    React.useImperativeHandle(ref, () => incrementInput.current!, []);

    const increment = () => {
      incrementInput.current?.stepUp();
      // Supports onchange events
      incrementInput.current?.dispatchEvent(
        new Event('change', { bubbles: true })
      );
      // Disbale when hitting max
      setHitMax(incrementInput.current?.value === incrementInput.current?.max);
      setHitMin(incrementInput.current?.value === incrementInput.current?.min);
    };

    const decrement = () => {
      incrementInput.current?.stepDown();
      // Supports onchange events
      incrementInput.current?.dispatchEvent(
        new Event('change', { bubbles: true })
      );
      // Disbale when hitting min
      setHitMax(incrementInput.current?.value === incrementInput.current?.max);
      setHitMin(incrementInput.current?.value === incrementInput.current?.min);
    };

    return (
      <div className="flex justify-between items-center rounded-lg border p-2.5">

        <button
          type="button"
          disabled={hitMin}
          onClick={decrement}
          aria-label="decrease"
          className="group text-gray-500 disabled:cursor-not-allowed disabled:opacity-50 mr-2"
        >
          <Minus className="w-4" />
        </button>

        <div className="relative flex-grow">
          <input
            type="number"
            className="no-steps w-fit border-0 bg-transparent p-0 text-center"
            ref={incrementInput}
            {...props}
          />
          {symbol && <span className="absolute right-4 top-0">{symbol}</span>}
        </div>

        <button
          type="button"
          disabled={hitMax}
          onClick={increment}
          aria-label="increase"
          className="group text-gray-500 disabled:cursor-not-allowed disabled:opacity-50 ml-2"
        >
          <Plus className="w-4" />
        </button>


      </div>
    );
  }
);
NumberInput.displayName = 'IncrementorInput';

export { NumberInput };
