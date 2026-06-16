// resources/js/hooks/useBinarySearch.ts

import { useCallback } from 'react';

/**
 * هوك البحث الثنائي الحقيقي (Binary Search)
 * يعمل فقط إذا كانت المصفوفة مرتبة أبجدياً بناءً على الحقل المُستخرج بـ getKey
 * الكفاءة: O(log n) - ممتاز للمطابقة التامة في القوائم الضخمة
 */
export function useBinarySearch<T>(
  items: T[],
  getKey: (item: T) => string
) {
  const binarySearch = useCallback((query: string): T[] => {
    // إذا كان نص البحث فارغاً، نعيد القائمة كاملة
    if (!query.trim()) return items;

    const q = query.toLowerCase().trim();
    
    let left = 0;
    let right = items.length - 1;

    // تبدأ حلقة البحث الثنائي وقوانينها هنا
    while (left <= right) {
      // 1. حساب نقطة المنتصف لتجنب مشاكل الذاكرة
      const mid = Math.floor((left + right) / 2);
      
      // 2. استخراج النص من العنصر الحالي وتحويله لأحرف صغيرة
      const currentKey = getKey(items[mid]).toLowerCase();

      // 3. مقارنة النص المطلوب بالنص الحالي
      if (currentKey === q) {
        // وجدنا المريض أو العنصر المطابق تماماً! نعيده داخل مصفوفة
        return [items[mid]];
      }

      // إذا كان النص الحالي أصغر أبجدياً من المطلوب، نلغي النصف الأيسر
      if (currentKey < q) {
        left = mid + 1;
      } 
      // إذا كان النص الحالي أكبر أبجدياً من المطلوب، نلغي النصف الأيمن
      else {
        right = mid - 1;
      }
    }

    // إذا انتهت الحلقة ولم نجد أي تطابق تام
    return [];
  }, [items, getKey]);

  return binarySearch;
}