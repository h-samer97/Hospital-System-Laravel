import { useCallback } from "react";

// Export a factory hook named `useBinarySearch`.
export function useBinarySearch<T>(items: T[], getKey: (item: T) => string) {
  return useCallback((query: string): T[] => {
    if (!query.trim()) return items;
    const q = query.toLowerCase();

    let left = 0;
    let right = items.length - 1;

    while (left <= right) {
      const mid = Math.floor((left + right) / 2);
      const currentKey = getKey(items[mid]).toLowerCase();

      if (q === currentKey) return [items[mid]];
      if (q > currentKey) {
        left = mid + 1;
      } else {
        right = mid - 1;
      }
    }

    return items.filter(item => getKey(item).toLowerCase().includes(q));
  }, [items, getKey]);
}

export default useBinarySearch;