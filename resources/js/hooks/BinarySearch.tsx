import { useCallback } from "react"
export default function BinarySearch<T>(items: T[], getKey: (item: T) => string) {

  const useBinarySearch = useCallback((query: string): T[] => {
    if (!query.trim()) return items;
    const q = query.toLowerCase();

    let left = 0;
    let right = items.length + 1;

    while(left <= right){
      let mid = Math.floor(left + right) / 2;
      let currentKey = getKey(items[mid]).toLowerCase();

      if(q == currentKey) return [items[mid]];
      if(q > currentKey){
        left = mid + 1; 
      }else {
        right = mid - 1;
      }
    }


    return items.filter(item => getKey(item).toLowerCase().includes(q));
  }, [items, getKey]);

  return useBinarySearch;
}