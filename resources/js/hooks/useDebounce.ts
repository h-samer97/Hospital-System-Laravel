import { useEffect, useState } from "react";

export function useDebounce<T>(value: T, delay: 300) : T {
  const [debounceValue, setDebounceValue] = useState<T>(value);

  useEffect(() => {

    const timer = setTimeout(() => {

        setDebounceValue(value);
        return () => clearTimeout(timer);

    }, delay);

  }, [delay, value]);

  return debounceValue;
}