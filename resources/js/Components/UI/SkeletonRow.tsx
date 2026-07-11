import React from 'react';
import styles from './SkeletonRow.module.css';

interface Props {
  columns: number;  // عدد الأعمدة يتناسب مع الجدول
  rows?:   number;  // عدد الصفوف (افتراضي 5)
}

/**
 * Skeleton Screen — يعرض "هيكل" الجدول بينما البيانات تُحمَّل
 *
 * أفضل من Spinner لأن:
 * - يحافظ على Layout ثابت (لا قفزات)
 * - يُعطي إحساساً بالسرعة (Perceived Performance)
 * - تجربة مستخدم أفضل موثّقة في أبحاث Facebook/LinkedIn
 */
export default function SkeletonRow({ columns, rows = 5 }: Props) {
  return (
    <>
      {Array.from({ length: rows }).map((_, rowIdx) => (
        <tr key={rowIdx} className={styles.skeletonRow}>
          {Array.from({ length: columns }).map((_, colIdx) => (
            <td key={colIdx}>
              <div
                className={styles.skeletonCell}
                
                style={{ width: `${60 + (colIdx % 3) * 15}%` }}
              />
            </td>
          ))}
        </tr>
      ))}
    </>
  );
}