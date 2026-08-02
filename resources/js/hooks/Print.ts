import { useState, useCallback } from "react";

export default function usePrint() {
  const [isPrinting, setIsPrint] = useState<boolean>(false);
  const Print = useCallback(() => {

      setIsPrint(true);

      requestAnimationFrame(() => {

        window.print();

        setIsPrint(false);

      });

  },[]);

  const download = useCallback((url: string) => {
      window.open(url, '_blank');
  }, []);

  return { print: Print, download, isPrinting }

}