1. Jelaskan dengan tingkat kompleksitas dengan pendekatan notasi O(n) untuk algoritma-algoritma di bawah ini: 
 `def get_first_item(arr):        return arr[0] `
 
 `def check_duplicate(arr):       for outer in range(len(arr)):              for inner in range(len(arr)):                    if outer == inner:                           continue                    if arr[outer] == arr[inner]:                           return true   
  return false`   
 
 `def Fibonacci(number):        if number <= 1:             return number        return Fibonacci(number - 2) + Fibonacci(number - 1)`
  
  Jawaban:
  1. get_first_item() = O(1) karena melambangkan sebuah operasi pendefinisian
  2. check_duplicate() = O(n^2) karena melambangkan sebuah operasi perulangan
